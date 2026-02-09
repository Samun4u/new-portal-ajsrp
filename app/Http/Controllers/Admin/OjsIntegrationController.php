<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Services\OjsApiService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OjsIntegrationController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $query = ClientOrderSubmission::with([
                'journal',
                'client_order.client',
                'authors',
                'finalMetadata',
                'galleyFiles'
            ])
            ->whereNotNull('acceptance_date')
            ->where('metadata_status', 'approved')
            ->whereHas('galleyFiles', function($q) {
                $q->where('status', 'approved');
            });

            // Filter by journal if provided
            if ($request->journal_id) {
                $query->where('journal_id', $request->journal_id);
            }

            // Filter by status
            if ($request->status) {
                if ($request->status === 'ready') {
                    $query->where(function($q) {
                        $q->where('approval_status', 'galley_ready')
                          ->orWhereNull('ojs_article_id');
                    });
                } elseif ($request->status === 'submitted') {
                    $query->whereNotNull('ojs_article_id');
                } elseif ($request->status === 'published') {
                    $query->where('approval_status', 'published');
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

            $data['pageTitle'] = __('OJS QuickSubmit Integration');
            $data['submissions'] = $query->orderBy('acceptance_date', 'desc')->paginate(20);
            $data['journals'] = \App\Models\Journal::where('status', 'active')->get();
            $data['filters'] = $request->only(['journal_id', 'status', 'search']);
            $data['activeOrder'] = 'active';
            $data['activeOjsIntegration'] = 'active';

            return view('admin.ojs.index', $data);
        } catch (\Exception $e) {
            Log::error('OJS integration index error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function quickSubmitData($submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'finalMetadata',
                'authors',
                'journal',
                'issue',
                'galleyFiles.file'
            ])->findOrFail($submissionId);

            $data['pageTitle'] = __('OJS QuickSubmit Data');
            $data['submission'] = $submission;
            $data['activeOrder'] = 'active';

            return view('admin.ojs.quicksubmit-data', $data);
        } catch (\Exception $e) {
            Log::error('OJS QuickSubmit data error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function updatePublication(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::findOrFail($submissionId);

            $request->validate([
                'ojs_article_url' => 'nullable|url|max:500',
                'ojs_article_id' => 'nullable|string|max:255',
                'publication_date' => 'nullable|date',
            ]);

            $submission->ojs_article_url = $request->ojs_article_url;
            $submission->ojs_article_id = $request->ojs_article_id;

            if ($request->publication_date) {
                $submission->publication_date = $request->publication_date;
            }

            if ($request->ojs_article_url || $request->ojs_article_id || $request->publication_date) {
                $submission->approval_status = 'published';
            }

            $submission->save();

            DB::commit();
            return $this->success([], __('Publication status updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Publication update error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while updating publication status.'));
        }
    }

    /**
     * Task 18: Auto-submit article to OJS via API
     */
    public function autoSubmit(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'finalMetadata',
                'authors',
                'journal',
                'issue',
                'galleyFiles.file'
            ])->findOrFail($submissionId);

            // Validate prerequisites
            if ($submission->metadata_status !== 'approved') {
                return $this->error([], __('Final metadata must be approved before submitting to OJS.'));
            }

            // Avoid duplicate submissions
            if ($submission->ojs_article_id) {
                return $this->error([], __('This article has already been submitted to OJS.'));
            }

            if (!$submission->journal || !$submission->journal->ojs_context) {
                return $this->error([], __('Journal OJS context is not configured.'));
            }

            $approvedGalley = $submission->galleyFiles()
                ->where('status', 'approved')
                ->first();

            if (!$approvedGalley) {
                return $this->error([], __('No approved galley version found.'));
            }

            // Submit to OJS
            $ojsService = new OjsApiService();
            $result = $ojsService->submitArticle($submission);

            if (!empty($result['success']) && $result['success'] === true) {
                // Persist OJS identifiers on successful submission
                $submission->ojs_article_id = $result['article_id'] ?? null;
                $submission->ojs_article_url = $result['article_url'] ?? null;

                if ($submission->ojs_article_id || $submission->ojs_article_url) {
                    $submission->approval_status = 'published';
                }

                $submission->save();

                return $this->success([
                    'article_id' => $submission->ojs_article_id,
                    'article_url' => $submission->ojs_article_url,
                ], __('Article submitted to OJS successfully.'));
            }

            return $this->error([], __('OJS submission failed: ') . ($result['error'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('OJS auto-submit error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while submitting to OJS: ') . $e->getMessage());
        }
    }
}
