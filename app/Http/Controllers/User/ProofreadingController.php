<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Models\ProofFile;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProofreadingController extends Controller
{
    use ResponseTrait;

    public function review($proof_id)
    {
        try {
            $proofId = decrypt($proof_id);
            $proof = ProofFile::with(['submission.authors', 'submission.journal', 'file', 'uploadedBy'])
                ->findOrFail($proofId);

            // Verify author owns this submission
            $submission = $proof->submission;
            if (!$submission) {
                abort(404, __('Submission not found.'));
            }

            $clientOrder = $submission->client_order;
            if (!$clientOrder || $clientOrder->client_id !== auth()->id()) {
                abort(403, __('You do not have permission to view this proof.'));
            }

            $data['pageTitle'] = __('Review Proof Version');
            $data['proof'] = $proof;
            $data['submission'] = $submission;
            $data['activeOrder'] = 'active';

            return view('user.proofreading.review', $data);
        } catch (\Exception $e) {
            Log::error('Proof review error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function approveProof(Request $request, $proof_id)
    {
        try {
            DB::beginTransaction();

            $proofId = decrypt($proof_id);
            $proof = ProofFile::with('submission.client_order')->findOrFail($proofId);

            // Verify author owns this submission
            $submission = $proof->submission;
            if (!$submission) {
                return redirect()->back()->with('error', __('Submission not found.'));
            }

            $clientOrder = $submission->client_order;
            if (!$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            if ($clientOrder->client_id !== auth()->id()) {
                return redirect()->back()->with('error', __('You do not have permission to approve this proof.'));
            }

            if ($proof->status !== 'pending') {
                return redirect()->back()->with('error', __('This proof has already been reviewed.'));
            }

            // Update proof status
            $proof->status = 'approved';
            $proof->reviewed_by = auth()->id();
            $proof->reviewed_at = now();
            $proof->save();

            // Update submission status
            $submission->approval_status = 'proof_approved';
            $submission->save();

            // Notify editor
            $brevoService = new BrevoService();
            $editorEmails = []; // Get editor emails - implement based on your system

            if (!empty($editorEmails)) {
                $emailSubject = __('Proof Approved by Author');
                $emailBody = $this->buildProofApprovedEmail($submission, $proof);

                $brevoService->sendEmail(
                    implode(',', $editorEmails),
                    null,
                    $emailSubject,
                    $emailBody
                );
            }

            DB::commit();
            if (!$submission || !$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }
            return redirect()->route('user.orders.dashboard', $clientOrder->order_id)
                ->with('success', __('Proof approved successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proof approval error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while approving proof.'));
        }
    }

    public function requestCorrections(Request $request, $proof_id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'corrections_note' => 'required|string|min:10|max:2000',
            ], [
                'corrections_note.required' => __('Please provide corrections note.'),
                'corrections_note.min' => __('Corrections note must be at least 10 characters.'),
            ]);

            $proofId = decrypt($proof_id);
            $proof = ProofFile::with('submission.client_order')->findOrFail($proofId);

            // Verify author owns this submission
            $submission = $proof->submission;
            if (!$submission) {
                return redirect()->back()->with('error', __('Submission not found.'));
            }

            $clientOrder = $submission->client_order;
            if (!$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            if ($clientOrder->client_id !== auth()->id()) {
                return redirect()->back()->with('error', __('You do not have permission to request corrections.'));
            }

            if ($proof->status !== 'pending') {
                return redirect()->back()->with('error', __('This proof has already been reviewed.'));
            }

            // Update proof status
            $proof->status = 'corrections_requested';
            $proof->corrections_requested = $request->corrections_note;
            $proof->reviewed_by = auth()->id();
            $proof->reviewed_at = now();
            $proof->save();

            // Notify editor
            $brevoService = new BrevoService();
            $editorEmails = []; // Get editor emails - implement based on your system

            if (!empty($editorEmails)) {
                $emailSubject = __('Corrections Requested for Proof');
                $emailBody = $this->buildCorrectionsRequestedEmail($submission, $proof);

                $brevoService->sendEmail(
                    implode(',', $editorEmails),
                    null,
                    $emailSubject,
                    $emailBody
                );
            }

            DB::commit();
            if (!$submission || !$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }
            return redirect()->route('user.orders.dashboard', $clientOrder->order_id)
                ->with('success', __('Corrections requested. Editor has been notified.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proof corrections request error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while requesting corrections.'));
        }
    }

    private function buildProofApprovedEmail($submission, $proof)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #28a745;">' . __('Proof Approved') . '</h2>';
        $body .= '<p>' . __('The author has approved proof version ' . $proof->version . '.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '</body></html>';
        return $body;
    }

    private function buildCorrectionsRequestedEmail($submission, $proof)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #ffc107;">' . __('Corrections Requested') . '</h2>';
        $body .= '<p>' . __('The author has requested corrections for proof version ' . $proof->version . '.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<h3>' . __('Requested Corrections') . '</h3>';
        $body .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">';
        $body .= '<p>' . nl2br(e($proof->corrections_requested)) . '</p>';
        $body .= '</div>';
        $body .= '</body></html>';
        return $body;
    }
}
