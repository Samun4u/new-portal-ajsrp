<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderSubmission;
use App\Models\ClientOrderSubmissionRevision;
use App\Models\ClientOrderSubmissionRevisionFile;
use App\Models\FileManager;
use App\Models\Reviews;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReviewerReviewController extends Controller
{
    use ResponseTrait;

    protected array $checklistKeys = [
        'title',
        'abstract',
        'methods',
        'ethics',
        'results',
        'discussion',
        'references',
        'language',
        'figures',
    ];

    public function show(Reviews $review)
    {
        $this->ensureReviewerOwnsReview($review, false);

        $review->load([
            'client_order_submission.journal',
            'client_order_submission.article_type',
            'client_order_submission.authors',
            'client_order_submission.supplyment_material_files',
        ]);

        $submission = $review->client_order_submission;

        $order = $submission
            ? ClientOrder::with(['assignee', 'client'])->where('order_id', $submission->client_order_id)->first()
            : ClientOrder::with(['assignee', 'client'])->where('order_id', $review->client_order_id)->first();

        $assignment = $order
            ? $order->assignee()
                ->where('assigned_to', auth()->id())
                ->orderByDesc('created_at')
                ->first()
            : null;

        if ($assignment) {
            $assignment->loadMissing(['assigner', 'reviewer']);
        }

        $allReviews = $submission
            ? Reviews::with('reviewer')
                ->where('client_order_submission_id', $submission->id)
                ->orderBy('created_at')
                ->get()
            : collect([$review]);

        // Get version history for this reviewer
        $versionHistory = $submission
            ? Reviews::where('client_order_submission_id', $submission->id)
                ->where('reviewer_id', $review->reviewer_id)
                ->orderByRaw('COALESCE(version, 1)')
                ->orderBy('created_at')
                ->get()
            : collect([$review]);

        // Get current version number (if not set, calculate it)
        $currentVersion = $review->version ?? 1;
        $currentRound = $review->round ?? Reviews::getCurrentRound($review->client_order_submission_id ?? 0);

        // Load all author revisions grouped by round so reviewers/editors can see history contextually
        $revisionsByRound = collect();
        $latestRevision = null;
        $authorResponseFile = null;
        if ($submission) {
            $allRevisions = ClientOrderSubmissionRevision::with(['attachments.file'])
                ->where('client_order_submission_id', $submission->id)
                ->orderByRaw('COALESCE(version, 1)')
                ->orderBy('created_at')
                ->get()
                ->map(function ($revision) {
                    if (function_exists('getFileUrl')) {
                        $revision->manuscript_url = $revision->manuscript_file_id
                            ? getFileUrl($revision->manuscript_file_id)
                            : null;
                        $revision->response_url = $revision->response_file_id
                            ? getFileUrl($revision->response_file_id)
                            : null;
                    } else {
                        $revision->manuscript_url = null;
                        $revision->response_url = null;
                    }

                    $revision->attachment_links = $revision->attachments
                        ? $revision->attachments->map(function ($attachment) {
                            return [
                                'label' => $attachment->label ?? __('Attachment'),
                                'url' => function_exists('getFileUrl') ? getFileUrl($attachment->file_id) : null,
                            ];
                        })
                        : collect();

                    return $revision;
                });

            $revisionsByRound = $allRevisions->groupBy(function ($revision) {
                return $revision->version ?? 1;
            });

            $latestRevision = $allRevisions->sortByDesc(function ($revision) {
                return [$revision->version ?? 1, $revision->created_at];
            })->first();

            if ($currentRound > 1 && $latestRevision && $latestRevision->response_file_id) {
                $authorResponseFile = function_exists('getFileUrl') ? getFileUrl($latestRevision->response_file_id) : null;
            }
        }

        $statistics = [
            'total_reviews' => $allReviews->count(),
            'completed_reviews' => $allReviews->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)->count(),
            'pending_reviews' => $allReviews->where('status', '!=', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)->count(),
            'average_rating' => $allReviews->avg('quality_rating'),
        ];
        $peerReviews = $allReviews->where('id', '!=', $review->id)->values();

        $invitationStatus = $review->invitation_status ?? optional($assignment)->invitation_status ?? 'pending';
        $dueAt = $assignment?->due_at;
        $daysRemaining = $dueAt ? Carbon::now()->diffInDays($dueAt, false) : null;

        // Determine which manuscript file to show (latest revision or original)
        $manuscriptFileId = null;
        $manuscriptFileUrl = null;
        if ($submission) {
            if ($latestRevision) {
                // Show revised manuscript if available
                $manuscriptFileId = $latestRevision->manuscript_file_id;
                $manuscriptFileUrl = function_exists('getFileUrl') ? getFileUrl($latestRevision->manuscript_file_id) : null;
            } else {
                // Show original manuscript
                $manuscriptFileId = $submission->full_article_file;
                $manuscriptFileUrl = $submission->full_article_file
                    ? (function_exists('getFileUrl') ? getFileUrl($submission->full_article_file) : null)
                    : null;
            }
        }

        $files = [
            'manuscript' => $manuscriptFileUrl,
            'manuscript_file_id' => $manuscriptFileId,
            'revised_manuscript' => $latestRevision ? (function_exists('getFileUrl') ? getFileUrl($latestRevision->manuscript_file_id) : null) : null,
            'author_response' => $authorResponseFile,
            'cover_letter' => $submission && $submission->covert_letter_file
                ? (function_exists('getFileUrl') ? getFileUrl($submission->covert_letter_file) : null)
                : null,
            'supplements' => ($submission?->supplyment_material_files ?? collect())->map(function ($file) {
                $url = function_exists('getFileUrl') ? getFileUrl($file->file_id) : null;
                return [
                    'id' => $file->file_id,
                    'url' => $url,
                ];
            }),
        ];

        $checklist = collect($this->checklistKeys)->mapWithKeys(function ($key) use ($review) {
            $checks = (array) ($review->specific_checks ?? []);
            return [$key => (bool) ($checks[$key] ?? false)];
        });

        $timeline = collect([
            [
                'label' => __('Invitation sent'),
                'timestamp' => $assignment?->invited_at ?? $review->invited_at,
            ],
            [
                'label' => __('Invitation responded'),
                'timestamp' => $assignment?->responded_at ?? $review->responded_at,
            ],
            [
                'label' => __('Review saved'),
                'timestamp' => $review->updated_at,
            ],
            [
                'label' => __('Review submitted'),
                'timestamp' => $review->submitted_at,
            ],
        ])->filter(fn($item) => !empty($item['timestamp']));

        return view('user.reviewer.reviewer-form', [
            'reviewer' => auth()->user(),
            'review' => $review,
            'submission' => $submission,
            'order' => $order,
            'assignment' => $assignment,
            'statistics' => $statistics,
            'invitationStatus' => $invitationStatus,
            'dueAt' => $dueAt,
            'daysRemaining' => $daysRemaining,
            'files' => $files,
            'checklist' => $checklist,
            'timeline' => $timeline,
            'peerReviews' => $peerReviews,
            'versionHistory' => $versionHistory,
            'currentVersion' => $currentVersion,
            'currentRound' => $currentRound,
            'latestRevision' => $latestRevision,
            'isNewVersion' => $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED && $review->submitted_at !== null,
            'revisionsByRound' => $revisionsByRound,
            'abstract' => $submission?->article_abstract,
            'manuscriptTitle' => $submission?->article_title,
        ]);
    }

    public function autosave(Request $request, Reviews $review)
    {
        $this->ensureReviewerOwnsReview($review);

        // Normalize incoming payload from the enhanced form (alternate field names)
        $this->normalizeFromTemplate($request);

        $payload = $this->validatePayload($request, false);
        $review->fill($payload);

        // Save Conflict of Interest fields directly
        if ($request->has('conflict_declared')) {
            $review->conflict_declared = $request->boolean('conflict_declared');
        }
        if ($request->has('conflict_details')) {
            $review->conflict_details = $request->input('conflict_details');
        }

        if ($review->status === SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW) {
            $review->status = SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS;
        }

        $review->progress = $this->calculateProgress($review);
        $ratings = array_filter([
            $review->rating_originality,
            $review->rating_methodology,
            $review->rating_results,
            $review->rating_clarity,
            $review->rating_significance,
            $review->rating_literature,
            $review->rating_data,
            $review->rating_ethics,
        ], static fn($v) => $v !== null && $v !== '');
        if (!empty($ratings)) {
            $review->quality_rating = round(array_sum($ratings) / count($ratings), 2);
        }
        $review->save();

        return $this->success([
            'progress' => $review->progress,
            'status' => $review->status,
        ], __('Progress saved successfully'));
    }

    public function submit(Request $request, Reviews $review)
    {
        // Log::info('reviewer_review_submit_request', $request->all());
        try {
            // $this->ensureReviewerOwnsReview($review);

            // Normalize incoming payload from the enhanced form (alternate field names)
            $this->normalizeFromTemplate($request);

            // Validate payload - this will throw ValidationException if validation fails
            $payload = $this->validatePayload($request, true);

            DB::beginTransaction();

            // Check if this review is already submitted - if so, create a new version
            $isAlreadySubmitted = $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED && $review->submitted_at !== null;

            if ($isAlreadySubmitted) {
                // Create a new version
                $currentRound = Reviews::getCurrentRound($review->client_order_submission_id);
                $nextVersion = Reviews::getNextVersionNumber($review->client_order_submission_id, $review->reviewer_id);

                // Create new review version
                $newReview = new Reviews();
                $newReview->client_order_submission_id = $review->client_order_submission_id;
                $newReview->client_order_id = $review->client_order_id;
                $newReview->reviewer_id = $review->reviewer_id;
                $newReview->version = $nextVersion;
                $newReview->round = $currentRound;
                $newReview->previous_version_id = $review->id;
                $newReview->fill($payload);
                $newReview->status = SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED;
                $newReview->submitted_at = Carbon::now();
                $newReview->progress = 100;

                // Save Conflict of Interest fields
                if ($request->has('conflict_declared')) {
                    $newReview->conflict_declared = $request->boolean('conflict_declared');
                }
                if ($request->has('conflict_details')) {
                    $newReview->conflict_details = $request->input('conflict_details');
                }

                // Copy structured fields
                if ($request->filled('questions_for_authors')) {
                    $newReview->questions_for_authors = $request->input('questions_for_authors');
                }
                if ($request->has('minor_issues')) {
                    $newReview->minor_issues = $request->input('minor_issues');
                }
                if ($request->has('major_issues')) {
                    $newReview->major_issues = $request->input('major_issues');
                }

                // Calculate quality rating
                $ratings = array_filter([
                    $newReview->rating_originality,
                    $newReview->rating_methodology,
                    $newReview->rating_results,
                    $newReview->rating_clarity,
                    $newReview->rating_significance,
                    $newReview->rating_literature,
                    $newReview->rating_data,
                    $newReview->rating_ethics,
                ], static fn($v) => $v !== null && $v !== '');
                if (!empty($ratings)) {
                    $newReview->quality_rating = round(array_sum($ratings) / count($ratings), 2);
                }

                $newReview->save();

                DB::commit();

                return $this->success([
                    'review_id' => $newReview->id,
                    'version' => $newReview->version,
                    'round' => $newReview->round,
                    'progress' => $newReview->progress,
                    'status' => $newReview->status,
                    'redirect_url' => route('user.dashboard'),
                ], __('Review Version :version submitted successfully', ['version' => $newReview->version]));
            } else {
                // First version - update existing review
                // Set version and round if not already set
                if (!$review->version) {
                    $review->version = 1;
                }
                if (!$review->round) {
                    $review->round = Reviews::getCurrentRound($review->client_order_submission_id);
                }

                $review->fill($payload);
                $review->status = SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED;
                $review->submitted_at = Carbon::now();
                $review->progress = 100;

                // Save Conflict of Interest fields
                if ($request->has('conflict_declared')) {
                    $review->conflict_declared = $request->boolean('conflict_declared');
                }
                if ($request->has('conflict_details')) {
                    $review->conflict_details = $request->input('conflict_details');
                }

                // Copy structured fields
                if ($request->filled('questions_for_authors')) {
                    $review->questions_for_authors = $request->input('questions_for_authors');
                }
                if ($request->has('minor_issues')) {
                    $review->minor_issues = $request->input('minor_issues');
                }
                if ($request->has('major_issues')) {
                    $review->major_issues = $request->input('major_issues');
                }

                // Calculate quality rating
                $ratings = array_filter([
                    $review->rating_originality,
                    $review->rating_methodology,
                    $review->rating_results,
                    $review->rating_clarity,
                    $review->rating_significance,
                    $review->rating_literature,
                    $review->rating_data,
                    $review->rating_ethics,
                ], static fn($v) => $v !== null && $v !== '');
                if (!empty($ratings)) {
                    $review->quality_rating = round(array_sum($ratings) / count($ratings), 2);
                }

                $review->save();

                DB::commit();

                return $this->success([
                    'review_id' => $review->id,
                    'version' => $review->version,
                    'round' => $review->round,
                    'progress' => $review->progress,
                    'status' => $review->status,
                    'redirect_url' => route('user.dashboard'),
                ], __('Review submitted successfully'));
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            $validationErrors = $e->errors();
            $missingFields = array_keys($validationErrors);

            Log::error('reviewer_review_validation_failed_in_submit', [
                'review_id' => $review->id,
                'user_id' => auth()->id(),
                'missing_fields' => $missingFields,
                'errors' => $validationErrors,
                'request_keys' => array_keys($request->all()),
            ]);

            return response()->json([
                'message' => 'Validation failed. Please check the errors below.',
                'errors' => $validationErrors,
                'missing_fields' => $missingFields,
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('reviewer_review_submit_failed', [
                'review_id' => $review->id,
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token']),
            ]);
            return response()->json([
                'message' => $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 422);
        }
    }

    public function submitRevisionPackage(Request $request, Reviews $review)
    {
        $this->ensureReviewerOwnsReview($review, false);

        $submission = $review->client_order_submission;

        if (!$submission && $review->client_order_submission_id) {
            $submission = ClientOrderSubmission::with('revisions')
                ->find($review->client_order_submission_id);
        }

        if (!$submission) {
            abort(404);
        }

        $submission->loadMissing('revisions');

        $request->validate([
            'manuscript_file' => 'required|file|max:51200',
            'response_file' => 'nullable|file|max:51200',
            'attachments.*' => 'nullable|file|max:51200',
            'response_summary' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $nextVersion = ($submission->revisions->max('version') ?? 0) + 1;

            $manuscriptUploader = new FileManager();
            $manuscript = $manuscriptUploader->upload('Revision', $request->file('manuscript_file'), null, null, 'revision');

            if (!$manuscript) {
                throw new \RuntimeException('Failed to store manuscript file.');
            }

            $responseFileId = null;

            if ($request->hasFile('response_file')) {
                $responseUploader = new FileManager();
                $responseFile = $responseUploader->upload('Revision', $request->file('response_file'));
                if (!$responseFile) {
                    throw new \RuntimeException('Failed to store response document.');
                }
                $responseFileId = $responseFile->id;
            }

            $revision = ClientOrderSubmissionRevision::create([
                'client_order_submission_id' => $submission->id,
                'client_order_id' => $submission->client_order_id ?? $review->client_order_id,
                'author_id' => auth()->id(),
                'version' => $nextVersion,
                'manuscript_file_id' => $manuscript->id,
                'response_file_id' => $responseFileId,
                'response_summary' => $request->input('response_summary'),
                'metadata' => [
                    'submitted_via' => 'reviewer_portal',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'attachments' => $request->hasFile('attachments') ? count($request->file('attachments')) : 0,
                ],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $index => $attachment) {
                    if (!$attachment) {
                        continue;
                    }

                    $attachmentUploader = new FileManager();
                    $uploadedAttachment = $attachmentUploader->upload('Revision', $attachment);

                    if ($uploadedAttachment) {
                        ClientOrderSubmissionRevisionFile::create([
                            'revision_id' => $revision->id,
                            'file_id' => $uploadedAttachment->id,
                            'label' => __('Attachment :number', ['number' => $index + 1]),
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('Revision package submitted successfully.'),
                    'redirect_url' => route('user.reviewer.reviews.show', $review),
                ]);
            }

            return back()->with('success', __('Revision package submitted successfully.'));
        } catch (\Throwable $throwable) {
            DB::rollBack();

            Log::error('reviewer_revision_submit_failed', [
                'review_id' => $review->id,
                'user_id' => auth()->id(),
                'message' => $throwable->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('Unable to submit your files right now. Please try again or contact support.'),
                ], 500);
            }

            return back()
                ->withErrors(['revision' => __('Unable to submit your files right now. Please try again or contact support.')])
                ->withInput();
        }
    }

    protected function ensureReviewerOwnsReview(Reviews $review, bool $requireAccepted = true): void
    {
        if ($review->reviewer_id !== auth()->id()) {
            abort(403);
        }

        if ($requireAccepted && $review->invitation_status !== 'accepted') {
            abort(403);
        }
    }

    protected function validatePayload(Request $request, bool $isSubmit): array
    {
        $recommendationRule = $isSubmit ? 'required' : 'nullable';
        // Allow ratings of 0–5 in case the template sends 0; controller may enforce completeness separately
        $ratingRule = $isSubmit ? 'required|integer|between:0,5' : 'nullable|integer|between:0,5';
        $commentRule = $isSubmit ? 'required|string' : 'nullable|string';

        $validationMessages = [
            'overall_recommendation.required' => 'The overall recommendation field is required.',
            'overall_recommendation.in' => 'The overall recommendation must be one of: accept, minor_revisions, major_revisions, or reject.',
            'rating_originality.required' => 'The originality rating is required.',
            'rating_originality.integer' => 'The originality rating must be a number.',
            'rating_originality.between' => 'The originality rating must be between 0 and 5.',
            'rating_methodology.required' => 'The methodology rating is required.',
            'rating_methodology.integer' => 'The methodology rating must be a number.',
            'rating_methodology.between' => 'The methodology rating must be between 0 and 5.',
            'rating_results.required' => 'The results rating is required.',
            'rating_results.integer' => 'The results rating must be a number.',
            'rating_results.between' => 'The results rating must be between 0 and 5.',
            'rating_clarity.required' => 'The clarity rating is required.',
            'rating_clarity.integer' => 'The clarity rating must be a number.',
            'rating_clarity.between' => 'The clarity rating must be between 0 and 5.',
            'rating_significance.required' => 'The significance rating is required.',
            'rating_significance.integer' => 'The significance rating must be a number.',
            'rating_significance.between' => 'The significance rating must be between 0 and 5.',
            'rating_literature.required' => 'The literature rating is required.',
            'rating_literature.integer' => 'The literature rating must be a number.',
            'rating_literature.between' => 'The literature rating must be between 0 and 5.',
            'rating_data.required' => 'The data rating is required.',
            'rating_data.integer' => 'The data rating must be a number.',
            'rating_data.between' => 'The data rating must be between 0 and 5.',
            'rating_ethics.required' => 'The ethics rating is required.',
            'rating_ethics.integer' => 'The ethics rating must be a number.',
            'rating_ethics.between' => 'The ethics rating must be between 0 and 5.',
            'comment_strengths.required' => 'The strengths comment is required.',
            'comment_weaknesses.required' => 'The weaknesses comment is required.',
            'comment_for_authors.required' => 'The comments for authors field is required.',
            'comment_for_editor.required' => 'The comments for editor field is required.',
        ];

        // Validate and log request data for debugging
        Log::info('reviewer_review_validation_request', [
            'is_submit' => $isSubmit,
            'request_keys' => array_keys($request->all()),
            'has_recommendation' => $request->has('overall_recommendation'),
            'has_ratings' => [
                'originality' => $request->has('rating_originality'),
                'methodology' => $request->has('rating_methodology'),
                'results' => $request->has('rating_results'),
                'clarity' => $request->has('rating_clarity'),
                'significance' => $request->has('rating_significance'),
                'literature' => $request->has('rating_literature'),
                'data' => $request->has('rating_data'),
                'ethics' => $request->has('rating_ethics'),
            ],
        ]);

        try {
            $data = $request->validate([
                'overall_recommendation' => [$recommendationRule, Rule::in(['accept', 'minor_revisions', 'major_revisions', 'reject'])],
                'rating_originality' => $ratingRule,
                'rating_methodology' => $ratingRule,
                'rating_results' => $ratingRule,
                'rating_clarity' => $ratingRule,
                'rating_significance' => $ratingRule,
                // New rating columns
                'rating_literature' => $ratingRule,
                'rating_data' => $ratingRule,
                'rating_ethics' => $ratingRule,
                'comment_strengths' => $commentRule,
                'comment_weaknesses' => $commentRule,
                'comment_for_authors' => $commentRule,
                // New structured comment columns
                'questions_for_authors' => 'nullable|string',
                'minor_issues' => 'nullable|string',
                'major_issues' => 'nullable|string',
                'comment_for_editor' => $commentRule,
                // Checklist is optional; when provided, it will be normalized into booleans
                'specific_checks' => ['nullable', 'array'],
            ], $validationMessages);
        } catch (ValidationException $e) {
            // Log missing fields for debugging with detailed information
            $errors = $e->errors();
            $missingFields = array_keys($errors);

            Log::error('reviewer_review_validation_failed_detailed', [
                'missing_fields' => $missingFields,
                'all_errors' => $errors,
                'request_keys' => array_keys($request->all()),
                'request_data' => $request->except(['_token']),
                'is_submit' => $isSubmit,
            ]);

            // Re-throw with detailed message
            throw $e;
        }

        $checks = collect($request->input('specific_checks', []))
            ->map(fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value)
            ->toArray();

        $data['specific_checks'] = collect($this->checklistKeys)
            ->mapWithKeys(fn($key) => [$key => $checks[$key] ?? false])
            ->toArray();

        return $data;
    }

    protected function calculateProgress(Reviews $review): int
    {
        $checklistComplete = !empty($review->specific_checks) && !in_array(false, (array) $review->specific_checks, true);

        $sections = collect([
            !empty($review->overall_recommendation),
            !is_null($review->rating_originality),
            !is_null($review->rating_methodology),
            !is_null($review->rating_results),
            !is_null($review->rating_clarity),
            !is_null($review->rating_significance),
            !empty($review->comment_strengths),
            !empty($review->comment_weaknesses),
            !empty($review->comment_for_authors),
            !empty($review->comment_for_editor),
            $checklistComplete,
        ]);

        $completed = $sections->filter()->count();
        $total = $sections->count();

        return $total === 0 ? 0 : (int) round(($completed / $total) * 100);
    }

    /**
     * Normalize alternate field names coming from the enhanced reviewer template
     * into the backend's expected keys for validation.
     */
    private function normalizeFromTemplate(Request $request): void
    {
        // Recommendation mapping (accept | minor | major | reject)
        if ($request->filled('recommendation') && !$request->filled('overall_recommendation')) {
            $map = [
                'accept' => 'accept',
                'minor' => 'minor_revisions',
                'major' => 'major_revisions',
                'reject' => 'reject',
            ];
            $request->merge([
                'overall_recommendation' => $map[$request->input('recommendation')] ?? $request->input('recommendation'),
            ]);
        }

        // Ratings mapping (0-5). If provided under short names, move to rating_*
        $ratingMap = [
            'originality' => 'rating_originality',
            'methodology' => 'rating_methodology',
            'results' => 'rating_results',
            'clarity' => 'rating_clarity',
            'significance' => 'rating_significance',
            // New columns
            'literature' => 'rating_literature',
            'data' => 'rating_data',
            'ethics' => 'rating_ethics',
        ];
        foreach ($ratingMap as $short => $full) {
            // Use has() so value '0' is not treated as empty
            if ($request->has($short) && !$request->has($full)) {
                $value = $request->input($short);
                $request->merge([$full => $value === '' ? null : (int) $value]);
            }
        }

        // Comments mapping
        if ($request->filled('summary') && !$request->filled('comment_for_authors')) {
            $request->merge(['comment_for_authors' => $request->input('summary')]);
        }
        if ($request->filled('strengths') && !$request->filled('comment_strengths')) {
            $request->merge(['comment_strengths' => $request->input('strengths')]);
        }
        if ($request->filled('weaknesses') && !$request->filled('comment_weaknesses')) {
            $request->merge(['comment_weaknesses' => $request->input('weaknesses')]);
        }
        if ($request->filled('confidential') && !$request->filled('comment_for_editor')) {
            $request->merge(['comment_for_editor' => $request->input('confidential')]);
        }

        // Structured sections -> new dedicated columns
        if ($request->filled('questions') && !$request->filled('questions_for_authors')) {
            $request->merge(['questions_for_authors' => $request->input('questions')]);
        }
        if ($request->filled('minor_issues') && !$request->filled('minor_issues')) {
            // no-op, already correct name from template
        }
        if ($request->filled('major_issues') && !$request->filled('major_issues')) {
            // no-op, already correct name from template
        }

        // Conflict of interest → save to dedicated fields
        $coiNone = $request->boolean('coi_none');
        $coiDeclare = $request->boolean('coi_declare');
        $coiDetails = trim((string) $request->input('coi_details', ''));

        if ($coiDeclare) {
            // User declared a conflict
            $request->merge([
                'conflict_declared' => true,
                'conflict_details' => $coiDetails ?: null,
            ]);
        } elseif ($coiNone) {
            // User declared no conflict
            $request->merge([
                'conflict_declared' => false,
                'conflict_details' => null,
            ]);
        }

        // Also add to editor comments for context (keeping this for backward compatibility)
        $editorAppend = [];
        if ($coiDeclare) {
            $editorAppend[] = "COI Declaration: Declared" . ($coiDetails ? "\nDetails: {$coiDetails}" : '');
        } elseif ($coiNone) {
            $editorAppend[] = "COI Declaration: None";
        }
        if (!empty($editorAppend)) {
            $existingEd = trim((string) $request->input('comment_for_editor', ''));
            // Only append if not already present
            if (strpos($existingEd, 'COI Declaration') === false) {
                $mergedEd = trim($existingEd . "\n\n" . implode("\n\n", $editorAppend));
                $request->merge(['comment_for_editor' => $mergedEd]);
            }
        }

        // Checklist mapping from template values -> canonical keys
        if (!$request->filled('specific_checks') && is_array($request->input('checks'))) {
            $present = array_fill_keys($this->checklistKeys, false);
            $map = [
                'title_accurate' => 'title',
                'abstract_scope' => 'abstract',
                'methods_appropriate' => 'methods',
                'results_clear' => 'results',
                'discussion_aligned' => 'discussion',
                'references_relevant' => 'references',
                'ethics_addressed' => 'ethics',
                'language_clear' => 'language',
                'figures_labeled' => 'figures',
                // extra template values we currently ignore:
                // 'data_available','no_plagiarism','conclusions_supported'
            ];
            foreach ($request->input('checks', []) as $val) {
                $key = $map[$val] ?? null;
                if ($key !== null && array_key_exists($key, $present)) {
                    $present[$key] = true;
                }
            }
            $request->merge(['specific_checks' => $present]);
        }
    }
}

