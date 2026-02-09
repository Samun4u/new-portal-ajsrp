<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Models\GalleyFile;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GalleyController extends Controller
{
    use ResponseTrait;

    public function review($galley_id)
    {
        try {
            $galleyId = decrypt($galley_id);
            $galley = GalleyFile::with(['submission.authors', 'submission.journal', 'submission.client_order', 'file', 'uploadedBy'])->findOrFail($galleyId);

            $submission = $galley->submission;
            $clientOrder = $submission->client_order;

            if (!$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            if (!$clientOrder || $clientOrder->client_id !== auth()->id()) {
                abort(403, __('You do not have permission to view this galley.'));
            }

            $data['pageTitle'] = __('Review Galley Version');
            $data['galley'] = $galley;
            $data['submission'] = $submission;
            $data['activeOrder'] = 'active';

            return view('user.galley.review', $data);
        } catch (\Exception $e) {
            Log::error('Galley review error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function approveGalley(Request $request, $galley_id)
    {
        try {
            DB::beginTransaction();

            $galleyId = decrypt($galley_id);
            $galley = GalleyFile::with('submission.client_order')->findOrFail($galleyId);

            $submission = $galley->submission;
            $clientOrder = $submission->client_order;

            if (!$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            if ($clientOrder->client_id !== auth()->id()) {
                return redirect()->back()->with('error', __('You do not have permission to approve this galley.'));
            }

            if ($galley->status !== 'pending') {
                return redirect()->back()->with('error', __('This galley has already been reviewed.'));
            }

            $galley->status = 'approved';
            $galley->reviewed_by = auth()->id();
            $galley->reviewed_at = now();
            $galley->save();

            // Move submission to galley_ready state
            $submission->approval_status = 'galley_ready';
            $submission->workflow_stage = 'galley';
            $submission->save();

            // After galley approval, attempt automatic OJS submission if all prerequisites are met.
            try {
                $submission->loadMissing(['finalMetadata', 'journal', 'issue', 'galleyFiles.file']);

                // Avoid duplicate submissions
                if (!$submission->ojs_article_id && $submission->metadata_status === 'approved') {
                    if ($submission->journal && $submission->journal->ojs_context) {
                        $approvedGalley = $submission->galleyFiles()
                            ->where('status', 'approved')
                            ->first();

                        if ($approvedGalley) {
                            $ojsService = new \App\Services\OjsApiService();
                            $result = $ojsService->submitArticle($submission);

                            if (!empty($result['success']) && $result['success'] === true) {
                                $submission->ojs_article_id = $result['article_id'] ?? null;
                                $submission->ojs_article_url = $result->article_url ?? ($result['article_url'] ?? null);

                                // Mark as published in our system once OJS accepts the article
                                if ($submission->ojs_article_id || $submission->ojs_article_url) {
                                    $submission->approval_status = 'published';
                                }

                                $submission->save();
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Do not break user flow if OJS integration fails; it can be retried from the OJS integration screen.
                \Illuminate\Support\Facades\Log::error('Auto OJS submission after galley approval failed: ' . $e->getMessage());
            }

            DB::commit();
            return redirect()->route('user.orders.dashboard', $submission->client_order->order_id)
                ->with('success', __('Galley approved successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Galley approval error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while approving galley.'));
        }
    }

    public function requestCorrections(Request $request, $galley_id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'corrections_note' => 'required|string|min:10|max:2000',
            ], [
                'corrections_note.required' => __('Please provide corrections note.'),
                'corrections_note.min' => __('Corrections note must be at least 10 characters.'),
            ]);

            $galleyId = decrypt($galley_id);
            $galley = GalleyFile::with('submission.client_order')->findOrFail($galleyId);

            $submission = $galley->submission;
            $clientOrder = $submission->client_order;

            if (!$clientOrder) {
                return redirect()->back()->with('error', __('Order not found.'));
            }

            if ($clientOrder->client_id !== auth()->id()) {
                return redirect()->back()->with('error', __('You do not have permission to request corrections.'));
            }

            if ($galley->status !== 'pending') {
                return redirect()->back()->with('error', __('This galley has already been reviewed.'));
            }

            $galley->status = 'corrections_requested';
            $galley->corrections_requested = $request->corrections_note;
            $galley->reviewed_by = auth()->id();
            $galley->reviewed_at = now();
            $galley->save();

            DB::commit();
            return redirect()->route('user.orders.dashboard', $submission->client_order->order_id)
                ->with('success', __('Corrections requested. Editor has been notified.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Galley corrections request error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while requesting corrections.'));
        }
    }
}
