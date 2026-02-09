<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Models\FileManager;
use App\Models\ProofFile;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProofreadingController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $query = ClientOrderSubmission::with(['journal', 'client_order.client', 'authors', 'proofFiles'])
                ->where(function($q) {
                    $q->where('workflow_stage', 'proofreading')
                      ->orWhere('approval_status', 'in_proofreading');
                });

            // Filter by journal if provided
            if ($request->journal_id) {
                $query->where('journal_id', $request->journal_id);
            }

            // Filter by status
            if ($request->status) {
                if ($request->status === 'pending') {
                    $query->whereDoesntHave('proofFiles', function($q) {
                        $q->where('status', 'approved');
                    });
                } elseif ($request->status === 'approved') {
                    $query->whereHas('proofFiles', function($q) {
                        $q->where('status', 'approved');
                    });
                }
            }

            // Filter by metadata status
            if ($request->has('metadata_status') && $request->metadata_status != 'all') {
                $query->where('metadata_status', $request->metadata_status);
            }

            // Search by title or author
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('article_title', 'like', '%' . $request->search . '%')
                      ->orWhereHas('authors', function($authorQuery) use ($request) {
                          $authorQuery->where('first_name', 'like', '%' . $request->search . '%')
                                     ->orWhere('last_name', 'like', '%' . $request->search . '%')
                                     ->orWhere('email', 'like', '%' . $request->search . '%');
                      });
                });
            }

            $data['pageTitle'] = __('Proofreading Management');
            $data['submissions'] = $query->orderBy('created_at', 'desc')->paginate(20);
            $data['journals'] = \App\Models\Journal::where('status', 'active')->get();
            $data['filters'] = $request->only(['journal_id', 'status', 'search']);
            $data['activeOrder'] = 'active';
            $data['activeProofreading'] = 'active';

            return view('admin.proofreading.index', $data);
        } catch (\Exception $e) {
            Log::error('Proofreading index error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function uploadProof(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::findOrFail($submissionId);

            // Validate that paper is accepted
            if ($submission->approval_status !== 'accepted') {
                DB::rollBack();
                return redirect()->back()->with('error', __('Paper must be accepted before uploading proof.'));
            }

            $request->validate([
                'proof_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                'notes' => 'nullable|string|max:1000',
                'review_type' => 'nullable|in:author,editor,reviewer',
                'assigned_reviewer_id' => 'required_if:review_type,reviewer|exists:users,id',
            ]);

            // Upload proof file
            $fileManager = new FileManager();
            $uploadedFile = $fileManager->upload('Proof', $request->file('proof_file'), null, null, 'proof_version');

            if (!$uploadedFile) {
                return $this->error([], __('Failed to upload proof file.'));
            }

            // Get next version number
            $nextVersion = ProofFile::where('client_order_submission_id', $submissionId)
                ->max('version') ?? 0;
            $nextVersion++;

            // Determine review type and assign reviewer if provided
            $reviewType = $request->review_type ?? 'author'; // author, editor, reviewer
            $assignedReviewerId = null;

            if ($reviewType === 'reviewer' && $request->assigned_reviewer_id) {
                $assignedReviewerId = $request->assigned_reviewer_id;
            } elseif ($reviewType === 'editor') {
                $assignedReviewerId = auth()->id(); // Editor reviewing themselves
            }

            // Create proof file record
            $proofFile = ProofFile::create([
                'client_order_submission_id' => $submissionId,
                'file_id' => $uploadedFile->id,
                'version' => (string) $nextVersion,
                'notes' => $request->notes,
                'status' => 'pending',
                'review_type' => $reviewType,
                'assigned_reviewer_id' => $assignedReviewerId,
                'uploaded_by' => auth()->id(),
            ]);

            // Update submission workflow
            $submission->workflow_stage = 'proofreading';
            $submission->approval_status = 'in_proofreading';
            $submission->save();

            // Notify author
            $brevoService = new BrevoService();
            $authorEmails = [];

            if ($submission->has_author && $submission->authors) {
                foreach ($submission->authors as $author) {
                    if (!empty($author->email)) {
                        $authorEmails[] = $author->email;
                    }
                }
            }

            if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                $authorEmails[] = $submission->client_order->client->email;
            }

            // Send notification based on review type
            if ($reviewType === 'author' && !empty($authorEmails)) {
                $emailSubject = __('Proof Version Ready for Review');
                $emailBody = $this->buildProofReadyEmail($submission, $proofFile);

                $brevoService->sendEmail(
                    implode(',', $authorEmails),
                    null,
                    $emailSubject,
                    $emailBody
                );
            } elseif ($reviewType === 'reviewer' && $assignedReviewerId) {
                $reviewer = \App\Models\User::find($assignedReviewerId);
                if ($reviewer && $reviewer->email) {
                    $emailSubject = __('Proof Version Assigned for Review');
                    $emailBody = $this->buildReviewerAssignmentEmail($submission, $proofFile);

                    $brevoService->sendEmail(
                        $reviewer->email,
                        null,
                        $emailSubject,
                        $emailBody
                    );
                }
            }
            // If review_type is 'editor', no email needed as editor will review it themselves

            DB::commit();
            $message = __('Proof uploaded successfully.');
            if ($reviewType === 'author') {
                $message .= ' ' . __('Author has been notified.');
            } elseif ($reviewType === 'reviewer') {
                $message .= ' ' . __('Reviewer has been notified.');
            }
            return $this->success(['proof_id' => $proofFile->id], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proof upload error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while uploading proof.'));
        }
    }

    public function listProofs($submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'proofFiles.file',
                'proofFiles.uploadedBy',
                'proofFiles.assignedReviewer',
                'proofFiles.reviewedBy'
            ])->findOrFail($submissionId);

            // Get available reviewers for assignment
            $reviewers = \App\Models\User::where('role', 'reviewer')
                ->where('status', 'active')
                ->get();

            $data['pageTitle'] = __('Proofreading Management');
            $data['submission'] = $submission;
            $data['reviewers'] = $reviewers;
            $data['activeOrder'] = 'active';

            return view('admin.proofreading.list', $data);
        } catch (\Exception $e) {
            Log::error('Proof list error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function assignReviewer(Request $request, $proof_id)
    {
        try {
            DB::beginTransaction();

            // Handle both encrypted and plain IDs
            try {
                $proofId = decrypt($proof_id);
            } catch (\Exception $e) {
                $proofId = $proof_id; // If decryption fails, assume it's already a plain ID
            }

            $proof = ProofFile::findOrFail($proofId);

            $request->validate([
                'assigned_reviewer_id' => 'required|exists:users,id',
            ]);

            $proof->review_type = 'reviewer';
            $proof->assigned_reviewer_id = $request->assigned_reviewer_id;
            $proof->save();

            // Notify assigned reviewer
            $reviewer = \App\Models\User::find($request->assigned_reviewer_id);
            if ($reviewer && $reviewer->email) {
                $brevoService = new BrevoService();
                $submission = $proof->submission;
                $emailSubject = __('Proof Version Assigned for Review');
                $emailBody = $this->buildReviewerAssignmentEmail($submission, $proof);

                $brevoService->sendEmail(
                    $reviewer->email,
                    null,
                    $emailSubject,
                    $emailBody
                );
            }

            DB::commit();
            return $this->success([], __('Reviewer assigned successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reviewer assignment error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while assigning reviewer.'));
        }
    }

    public function reviewProof(Request $request, $proof_id)
    {
        try {
            DB::beginTransaction();

            $proofId = decrypt($proof_id);
            $proof = ProofFile::with('submission')->findOrFail($proofId);

            // Check if current user can review (editor/admin or assigned reviewer)
            $canReview = false;
            if (in_array(auth()->user()->role, ['admin', 'editor'])) {
                $canReview = true;
            } elseif ($proof->review_type === 'reviewer' && $proof->assigned_reviewer_id === auth()->id()) {
                $canReview = true;
            }

            if (!$canReview) {
                return redirect()->back()->with('error', __('You do not have permission to review this proof.'));
            }

            if ($proof->status !== 'pending') {
                return redirect()->back()->with('error', __('This proof has already been reviewed.'));
            }

            $request->validate([
                'action' => 'required|in:approve,request_corrections',
                'review_notes' => 'nullable|string|max:2000',
                'corrections_requested' => 'required_if:action,request_corrections|string|min:10|max:2000',
            ]);

            if ($request->action === 'approve') {
                $proof->status = 'approved';
                $proof->review_notes = $request->review_notes;
            } else {
                $proof->status = 'corrections_requested';
                $proof->corrections_requested = $request->corrections_requested;
                $proof->review_notes = $request->review_notes;
            }

            $proof->reviewed_by = auth()->id();
            $proof->reviewed_at = now();
            $proof->save();

            // Update submission status if approved
            if ($proof->status === 'approved') {
                $submission = $proof->submission;
                // Mark proofreading as completed and move into galley/production stage
                $submission->approval_status = 'proof_approved';
                $submission->workflow_stage = 'galley';
                $submission->save();
            }

            DB::commit();
            return redirect()->route('admin.proofreading.list', encrypt($proof->submission->id))
                ->with('success', __('Proof reviewed successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proof review error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while reviewing proof.'));
        }
    }

    public function reviewProofPage($proof_id)
    {
        try {
            $proofId = decrypt($proof_id);
            $proof = ProofFile::with([
                'submission.authors',
                'submission.journal',
                'file',
                'uploadedBy',
                'assignedReviewer'
            ])->findOrFail($proofId);

            // Check if current user can review
            $canReview = false;
            if (in_array(auth()->user()->role, ['admin', 'editor'])) {
                $canReview = true;
            } elseif ($proof->review_type === 'reviewer' && $proof->assigned_reviewer_id === auth()->id()) {
                $canReview = true;
            }

            if (!$canReview) {
                abort(403, __('You do not have permission to review this proof.'));
            }

            $data['pageTitle'] = __('Review Proof Version');
            $data['proof'] = $proof;
            $data['submission'] = $proof->submission;
            $data['activeOrder'] = 'active';

            return view('admin.proofreading.review', $data);
        } catch (\Exception $e) {
            Log::error('Proof review page error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    private function buildReviewerAssignmentEmail($submission, $proofFile)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #007bff;">' . __('Proof Version Assigned for Review') . '</h2>';
        $body .= '<p>' . __('You have been assigned to review a proof version.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><strong>' . __('Proof Version') . ':</strong> ' . e($proofFile->version) . '</p>';
        if ($proofFile->notes) {
            $body .= '<p><strong>' . __('Notes') . ':</strong> ' . nl2br(e($proofFile->notes)) . '</p>';
        }
        $body .= '<p><a href="' . url('/admin/proofreading/review/' . encrypt($proofFile->id)) . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">' . __('Review Proof') . '</a></p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }

    private function buildProofReadyEmail($submission, $proofFile)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #007bff;">' . __('Proof Version Ready for Review') . '</h2>';
        $body .= '<p>' . __('A new proof version is ready for your review.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><strong>' . __('Proof Version') . ':</strong> ' . e($proofFile->version) . '</p>';
        if ($proofFile->notes) {
            $body .= '<p><strong>' . __('Notes') . ':</strong> ' . nl2br(e($proofFile->notes)) . '</p>';
        }
        $body .= '<p><a href="' . url('/user/proofreading/review/' . encrypt($proofFile->id)) . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">' . __('Review Proof') . '</a></p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }
}
