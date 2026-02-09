<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientInvoice;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderSubmission;
use App\Models\Reviews;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EditorDashboardController extends Controller
{
    protected array $stageProgress = [
        SUBMISSION_ORDER_STATUS_PENDING => 5,
        SUBMISSION_ORDER_STATUS_INCOMPLETE => 5,
        SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW => 15,
        SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED => 30,
        SUBMISSION_ORDER_STATUS_PENDING_PAYMENT => 35,
        SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED => 40,
        SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW => 60,
        SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS => 75,
        SUBMISSION_ORDER_STATUS_ACCEPTED => 80,
        SUBMISSION_ORDER_STATUS_PEER_REJECTED => 80,
        SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION => 90,
        SUBMISSION_ORDER_STATUS_PUBLISHED => 100,
    ];

    protected array $statusBadgeMap = [
        SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW => 'bg-info',
        SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED => 'bg-primary',
        SUBMISSION_ORDER_STATUS_PENDING_PAYMENT => 'bg-warning text-dark',
        SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED => 'bg-success',
        SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW => 'bg-secondary',
        SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS => 'bg-warning text-dark',
        SUBMISSION_ORDER_STATUS_ACCEPTED => 'bg-primary',
        SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION => 'bg-success',
        SUBMISSION_ORDER_STATUS_PUBLISHED => 'bg-success',
        SUBMISSION_ORDER_STATUS_INITIAL_REJECTED => 'bg-danger',
        SUBMISSION_ORDER_STATUS_PEER_REJECTED => 'bg-danger',
    ];

    public function index()
    {
        $now = Carbon::now();
        $lookAhead = $now->copy()->addDays(3);

        $statusCounts = ClientOrderSubmission::select('approval_status', DB::raw('COUNT(*) as total'))
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status');

        $totalSubmissions = ClientOrderSubmission::count();

        $activeStatuses = [
            SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW,
            SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED,
            SUBMISSION_ORDER_STATUS_PENDING_PAYMENT,
            SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED,
            SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW,
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
            SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
        ];

        $totalActive = ClientOrderSubmission::whereIn('approval_status', $activeStatuses)->count();

        $awaitingAssignment = ClientOrderSubmission::where('approval_status', SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED)
            ->whereHas('client_order', function ($query) {
                $query->whereDoesntHave('assignee', function ($assigneeQuery) {
                    $assigneeQuery->where('is_active', true);
                });
            })->count();

        $pendingPayment = ClientOrderSubmission::where('approval_status', SUBMISSION_ORDER_STATUS_PENDING_PAYMENT)->count();
        $peerReviewCount = $statusCounts[SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW] ?? 0;
        $revisionCount = $statusCounts[SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS] ?? 0;
        $publicationQueue = $statusCounts[SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION] ?? 0;
        $pendingInvoices = ClientInvoice::where('payment_status', PAYMENT_STATUS_PENDING)->count();

        $overdueReviews = $this->reviewAssignmentCount(function ($query) use ($now) {
            $query->where('client_order_assignees.due_at', '<', $now);
        });

        $dueSoonReviews = $this->reviewAssignmentCount(function ($query) use ($now, $lookAhead) {
            $query->whereBetween('client_order_assignees.due_at', [$now, $lookAhead]);
        });

        $averageDecisionTime = $this->formatAverageHours(
            ClientOrderSubmission::select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->whereNotNull('updated_at')
                ->value('avg_hours')
        );

        $stageBreakdown = collect($statusCounts)->map(function ($total, $status) use ($totalSubmissions) {
            return [
                'label' => $this->formatStatus($status),
                'value' => $total,
                'percent' => $totalSubmissions ? round(($total / $totalSubmissions) * 100) : 0,
                'badge' => $this->statusBadgeMap[$status] ?? 'bg-secondary',
            ];
        })->sortByDesc('value');

        $pipelineRecords = ClientOrderSubmission::with([
            'client_order.client:id,name',
            'journal:id,title',
            'article_type:id,name',
        ])
            ->latest('updated_at')
            ->take(12)
            ->get()
            ->map(function (ClientOrderSubmission $submission) {
                $order = $submission->client_order;
                $progress = $this->stageProgress[$submission->approval_status] ?? 0;

                return [
                    'title' => $submission->article_title,
                    'status' => $this->formatStatus($submission->approval_status),
                    'status_badge' => $this->statusBadgeMap[$submission->approval_status] ?? 'bg-secondary',
                    'order_id' => optional($order)->order_id,
                    'journal' => optional($submission->journal)->title,
                    'article_type' => optional($submission->article_type)->name,
                    'author' => optional(optional($order)->client)->name,
                    'progress' => $progress,
                    'updated_at' => optional($submission->updated_at)->diffForHumans(),
                ];
            });

        $assignments = ClientOrderAssignee::with([
            'order.client_order_submission' => function ($query) {
                $query->with(['journal:id,title', 'article_type:id,name']);
            },
            'reviewer:id,name,email',
        ])
            ->where('is_active', true)
            ->orderByRaw('client_order_assignees.due_at IS NULL, client_order_assignees.due_at ASC')
            ->take(10)
            ->get();

        $reviewMap = $this->loadReviewProgressMap($assignments);

        $activeReviews = $assignments->map(function (ClientOrderAssignee $assignment) use ($reviewMap, $now) {
            $submission = optional($assignment->order)->client_order_submission;
            $key = $submission ? $submission->id . '-' . $assignment->assigned_to : null;
            $review = $key ? $reviewMap->get($key) : null;
            $status = $review->status ?? SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW;

            return [
                'reviewer' => optional($assignment->reviewer)->name,
                'manuscript' => $submission?->article_title,
                'status' => $this->formatReviewStatus($status),
                'status_badge' => $this->reviewBadge($status),
                'progress' => $review->progress ?? 0,
                'due_at' => optional($assignment->due_at)->format('M d, Y'),
                'is_overdue' => $assignment->due_at ? $assignment->due_at->lt($now) && ($review->status ?? null) !== SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED : false,
                'invitation_status' => $review->invitation_status ?? 'pending',
                'keywords' => $submission?->article_keywords,
            ];
        })->filter(fn ($row) => !empty($row['manuscript']));

        $revisionQueueData = ClientOrderSubmission::with(['client_order.client:id,name', 'journal:id,title'])
            ->where('approval_status', SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS)
            ->orderBy('updated_at')
            ->take(8)
            ->get()
            ->map(function (ClientOrderSubmission $submission) {
                return [
                    'title' => $submission->article_title,
                    'author' => optional(optional($submission->client_order)->client)->name,
                    'journal' => optional($submission->journal)->title,
                    'last_update' => optional($submission->updated_at)->diffForHumans(),
                    'language' => strtoupper($submission->language ?? 'EN'),
                    'order_id' => optional($submission->client_order)->order_id,
                ];
            });

        $publicationQueueData = ClientOrderSubmission::with(['client_order.client:id,name', 'journal:id,title'])
            ->where('approval_status', SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION)
            ->orderBy('updated_at')
            ->take(6)
            ->get()
            ->map(function (ClientOrderSubmission $submission) {
                return [
                    'title' => $submission->article_title,
                    'journal' => optional($submission->journal)->title,
                    'author' => optional(optional($submission->client_order)->client)->name,
                    'language' => strtoupper($submission->language ?? 'EN'),
                    'order_id' => optional($submission->client_order)->order_id,
                    'updated_at' => optional($submission->updated_at)->format('M d, Y'),
                ];
            });

        $stats = [
            [
                'label' => __('Active manuscripts'),
                'value' => $totalActive,
                'subtext' => __('Currently moving through editorial workflow'),
            ],
            [
                'label' => __('Awaiting assignment'),
                'value' => $awaitingAssignment,
                'subtext' => __('Require reviewer selection'),
            ],
            [
                'label' => __('In peer review'),
                'value' => $peerReviewCount,
                'subtext' => __('Under evaluation by reviewers'),
            ],
            [
                'label' => __('Pending revisions'),
                'value' => $revisionCount,
                'subtext' => __('Awaiting author responses'),
            ],
            [
                'label' => __('Ready to publish'),
                'value' => $publicationQueue,
                'subtext' => __('Final acceptance queue'),
            ],
            [
                'label' => __('Pending invoices'),
                'value' => $pendingInvoices,
                'subtext' => __('Awaiting author payment confirmation'),
            ],
        ];

        $alerts = collect([
            $overdueReviews > 0 ? [
                'type' => 'danger',
                'icon' => 'fa-triangle-exclamation',
                'message' => trans_choice(
                    '{1} :count review assignment overdue|[2,*] :count review assignments overdue',
                    $overdueReviews,
                    ['count' => $overdueReviews]
                ),
            ] : null,
            $dueSoonReviews > 0 ? [
                'type' => 'warning',
                'icon' => 'fa-hourglass-half',
                'message' => trans_choice(
                    '{1} :count review due within 72 hours|[2,*] :count reviews due within 72 hours',
                    $dueSoonReviews,
                    ['count' => $dueSoonReviews]
                ),
            ] : null,
            $awaitingAssignment > 0 ? [
                'type' => 'info',
                'icon' => 'fa-user-plus',
                'message' => trans_choice(
                    '{1} :count manuscript awaiting reviewer assignment|[2,*] :count manuscripts awaiting reviewer assignment',
                    $awaitingAssignment,
                    ['count' => $awaitingAssignment]
                ),
            ] : null,
        ])->filter()->values();

        return view('admin.editor.dashboard', [
            'pageTitleParent' => __('Dashboard'),
            'pageTitle' => __('Editorial Control Center'),
            'activeEditorDashboard' => 'active',
            'stats' => $stats,
            'alerts' => $alerts,
            'averageDecisionTime' => $averageDecisionTime,
            'stageBreakdown' => $stageBreakdown,
            'pipelineRecords' => $pipelineRecords,
            'activeReviews' => $activeReviews,
            'revisionQueue' => $revisionQueueData,
            'publicationQueue' => $publicationQueueData,
        ]);
    }

    protected function reviewAssignmentCount(callable $callback): int
    {
        $query = ClientOrderAssignee::query()
            ->join('client_orders', 'client_order_assignees.order_id', '=', 'client_orders.id')
            ->join('client_order_submissions', 'client_orders.order_id', '=', 'client_order_submissions.client_order_id')
            ->leftJoin('reviews', function ($join) {
                $join->on('client_order_submissions.id', '=', 'reviews.client_order_submission_id')
                    ->on('client_order_assignees.assigned_to', '=', 'reviews.reviewer_id');
            })
            ->where('client_order_assignees.is_active', true)
            ->whereNotNull('client_order_assignees.due_at')
            ->where(function ($statusQuery) {
                $statusQuery->whereNull('reviews.status')
                    ->orWhere('reviews.status', '!=', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
            });

        $callback($query);

        return (int) $query->count();
    }

    protected function loadReviewProgressMap($assignments)
    {
        $submissionIds = $assignments->map(function (ClientOrderAssignee $assignment) {
            return optional(optional($assignment->order)->client_order_submission)->id;
        })->filter()->unique();

        $reviewerIds = $assignments->pluck('assigned_to')->unique();

        return Reviews::whereIn('client_order_submission_id', $submissionIds)
            ->whereIn('reviewer_id', $reviewerIds)
            ->get()
            ->keyBy(function (Reviews $review) {
                return $review->client_order_submission_id . '-' . $review->reviewer_id;
            });
    }

    protected function formatAverageHours($hours): string
    {
        if (is_null($hours)) {
            return __('Not enough data');
        }

        $hours = (float) $hours;

        if ($hours < 24) {
            return sprintf('%d %s', round($hours), __('hrs'));
        }

        $days = floor($hours / 24);
        $remainingHours = round($hours % 24);

        if ($remainingHours === 0) {
            return trans_choice(
                '{1} :count day turnaround|[2,*] :count days turnaround',
                $days,
                ['count' => $days]
            );
        }

        return sprintf(
            '%d %s %d %s',
            $days,
            __('days'),
            $remainingHours,
            __('hrs')
        );
    }

    protected function formatStatus(?string $status): string
    {
        if (is_null($status)) {
            return __('Unknown');
        }

        return ucwords(str_replace('_', ' ', $status));
    }

    protected function formatReviewStatus(?string $status): string
    {
        return match ($status) {
            SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED => __('Completed'),
            SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS => __('In progress'),
            SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW => __('Pending'),
            default => __('Pending'),
        };
    }

    protected function reviewBadge(string $status): string
    {
        return match ($status) {
            SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED => 'bg-success',
            SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS => 'bg-info',
            default => 'bg-secondary',
        };
    }
}

