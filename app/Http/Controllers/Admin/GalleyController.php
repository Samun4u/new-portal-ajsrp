<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Models\FileManager;
use App\Models\GalleyFile;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GalleyController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $query = ClientOrderSubmission::with(['journal', 'client_order.client', 'authors', 'galleyFiles'])
                ->where(function($q) {
                    $q->where('workflow_stage', 'galley')
                      ->orWhere('approval_status', 'galley_in_progress')
                      ->orWhere('approval_status', 'proof_approved');
                });

            // Filter by journal if provided
            if ($request->journal_id) {
                $query->where('journal_id', $request->journal_id);
            }

            // Filter by status
            if ($request->status) {
                if ($request->status === 'pending') {
                    $query->whereDoesntHave('galleyFiles', function($q) {
                        $q->where('status', 'approved');
                    });
                } elseif ($request->status === 'approved') {
                    $query->whereHas('galleyFiles', function($q) {
                        $q->where('status', 'approved');
                    });
                }
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

            $data['pageTitle'] = __('Galley (Final Layout) Management');
            $data['submissions'] = $query->orderBy('created_at', 'desc')->paginate(20);
            $data['journals'] = \App\Models\Journal::where('status', 'active')->get();
            $data['filters'] = $request->only(['journal_id', 'status', 'search']);
            $data['activeOrder'] = 'active';
            $data['activeGalley'] = 'active';

            return view('admin.galley.index', $data);
        } catch (\Exception $e) {
            Log::error('Galley index error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function uploadGalley(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::findOrFail($submissionId);

            if ($submission->approval_status !== 'proof_approved') {
                return $this->error([], __('Proof must be approved before uploading galley.'));
            }

            $request->validate([
                'galley_file' => 'required|file|mimes:pdf|max:20480',
                'notes' => 'nullable|string|max:1000',
            ]);

            $fileManager = new FileManager();
            $uploadedFile = $fileManager->upload('Galley', $request->file('galley_file'), null, null, 'galley_version');

            if (!$uploadedFile) {
                return $this->error([], __('Failed to upload galley file.'));
            }

            $nextVersion = GalleyFile::where('client_order_submission_id', $submissionId)->max('version') ?? 0;
            $nextVersion++;

            $galleyFile = GalleyFile::create([
                'client_order_submission_id' => $submissionId,
                'file_id' => $uploadedFile->id,
                'version' => (string) $nextVersion,
                'notes' => $request->notes,
                'status' => 'pending',
                'uploaded_by' => auth()->id(),
            ]);

            $submission->workflow_stage = 'galley';
            $submission->approval_status = 'galley_in_progress';
            $submission->save();

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

            if (!empty($authorEmails)) {
                $emailSubject = __('Galley Version Ready for Review');
                $emailBody = $this->buildGalleyReadyEmail($submission, $galleyFile);

                $brevoService->sendEmail(implode(',', $authorEmails), null, $emailSubject, $emailBody);
            }

            DB::commit();
            return $this->success(['galley_id' => $galleyFile->id], __('Galley uploaded successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Galley upload error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while uploading galley.'));
        }
    }

    public function listGalleys($submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['galleyFiles.file', 'galleyFiles.uploadedBy'])->findOrFail($submissionId);

            $data['pageTitle'] = __('Galley Management');
            $data['submission'] = $submission;
            $data['activeOrder'] = 'active';

            return view('admin.galley.list', $data);
        } catch (\Exception $e) {
            Log::error('Galley list error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    private function buildGalleyReadyEmail($submission, $galleyFile)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #007bff;">' . __('Galley Version Ready') . '</h2>';
        $body .= '<p>' . __('The final galley version is ready for your review.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><a href="' . route('user.submission.galley.review', encrypt($galleyFile->id)) . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">' . __('Review Galley') . '</a></p>';
        $body .= '</body></html>';
        return $body;
    }
}
