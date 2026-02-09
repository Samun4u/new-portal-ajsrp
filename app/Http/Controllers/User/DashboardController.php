<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\Reviews;
use App\Models\Ticket;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use ResponseTrait;


    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->ticketSummeryForClient($request);

        }

        if (auth()->user()->role == USER_ROLE_REVIEWER) {
            return view('user.reviewer.dashboard', $this->buildReviewerDashboardData());
        }

        $data['pageTitleParent'] = __('');
        $data['pageTitle'] = __('Dashboard');
        $data['isDashboard'] = true;
        $data['activeDashboard'] = 'active';
        $data['totalUser'] = 0;
        $data['totalCustomer'] = 0;
        $data['paymentPending'] = ClientInvoice::where(['payment_status' => PAYMENT_STATUS_PENDING, 'client_id' => auth()->id()])->count();
        $data['openTicket'] = Ticket::where(['client_id' => auth()->id()])
            ->whereIn('status',[TICKET_STATUS_OPEN,TICKET_STATUS_IN_PROGRESS])
            ->count();
        $data['completedTicket'] = Ticket::where(['client_id' => auth()->id()])
            ->whereIn('status',[TICKET_STATUS_RESOLVED,TICKET_STATUS_CLOSED])
            ->count();

        $data['openOrders'] = ClientOrder::where(['working_status' => WORKING_STATUS_WORKING, 'client_id' => auth()->id()])->count();
        $data['completedOrders'] = ClientOrder::where(['working_status' => WORKING_STATUS_COMPLETED, 'client_id' => auth()->id()])->count();

        $data['totalActiveSubmissions'] = ClientOrder::where(['client_id' => auth()->id()])->whereHas('client_order_submission',function($q){$q->where('approval_status', SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW);})->with('client_order_submission')->count();

        $data['totalPublishedPapers'] = ClientOrder::where(['client_id' => auth()->id()])->whereHas('client_order_submission',function($q){$q->where('approval_status', SUBMISSION_ORDER_STATUS_PUBLISHED);})->with('client_order_submission')->count();

        $data['totalPendingReviews'] = ClientOrder::where(['client_id' => auth()->id()])->whereHas('client_order_submission',function($q){$q->where('approval_status', SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW);})->with('client_order_submission')->count();

        return view('user.dashboard', $data);
    }

    public function orderSummery(Request $request)
    {
        return $this->dashboardService->orderSummeryForClient($request);
    }

    protected function buildReviewerDashboardData(): array
    {
        $assignments = ClientOrderAssignee::with(['order.client_order_submission' => function ($query) {
            $query->with([
                'journal',
                'article_type',
                'authors',
                'supplyment_material_files',
            ]);
        }])->where('assigned_to', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        $assignmentPayload = $assignments->map(function (ClientOrderAssignee $assignment) {
            $order = $assignment->order;
            $submission = optional($order)->client_order_submission;

            if ($order && $submission) {
                $review = Reviews::firstOrCreate(
                    [
                        'client_order_submission_id' => $submission->id,
                        'reviewer_id' => auth()->id(),
                    ],
                    [
                        'client_order_id' => $submission->client_order_id,
                        'status' => SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW,
                    ]
                );
            } else {
                $review = null;
            }

            return [
                'assignment' => $assignment,
                'order' => $order,
                'submission' => $submission,
                'review' => $review,
                'progress' => $review ? $this->calculateReviewProgress($review) : 0,
                'invitation_status' => $assignment->invitation_status ?? optional($review)->invitation_status,
                'invitation_token' => $review ? data_get($review->invitation_metadata, 'token') : null,
            ];
        })->filter(fn ($payload) => !empty($payload['submission']));

        $reviews = Reviews::where('reviewer_id', auth()->id())->get();
        $completedReviews = $reviews->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
        $activeReviews = $reviews->where('status', '!=', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
        $pendingInvitations = $reviews->where('invitation_status', 'pending')->count();

        $activeReviewId = request()->query('review');
        $activeAssignment = $assignmentPayload->first(function ($payload) use ($activeReviewId) {
            return $activeReviewId && $payload['review'] && $payload['review']->id == $activeReviewId;
        }) ?? $assignmentPayload->first();

        return [
            'pageTitleParent' => __(''),
            'pageTitle' => __('Reviewer Dashboard'),
            'assignments' => $assignmentPayload,
            'statistics' => [
                'total_reviews' => $reviews->count(),
                'active_reviews' => $activeReviews->count(),
                'completed_reviews' => $completedReviews->count(),
                'average_completion_time' => $this->calculateAverageCompletionTime($completedReviews),
                'quality_rating' => $reviews->avg('quality_rating') ? number_format((float)$reviews->avg('quality_rating'), 2) : __('N/A'),
                'pending_invitations' => $pendingInvitations,
            ],
            'activeAssignment' => $activeAssignment,
        ];
    }

    protected function calculateReviewProgress(Reviews $review): int
    {
        $checklistComplete = !empty($review->specific_checks) && !in_array(false, (array)$review->specific_checks, true);

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

    protected function calculateAverageCompletionTime($completedReviews): string
    {
        if ($completedReviews->isEmpty()) {
            return __('N/A');
        }

        $totalMinutes = $completedReviews->reduce(function ($carry, Reviews $review) {
            $finishedAt = $review->submitted_at ?? $review->updated_at ?? Carbon::now();
            $startedAt = $review->created_at ?? $finishedAt;
            return $carry + $finishedAt->diffInMinutes($startedAt);
        }, 0);

        if ($totalMinutes === 0) {
            return __('< 1 min');
        }

        $averageMinutes = $totalMinutes / max($completedReviews->count(), 1);

        if ($averageMinutes < 60) {
            return sprintf('%d %s', round($averageMinutes), __('mins'));
        }

        $hours = floor($averageMinutes / 60);
        $minutes = round($averageMinutes % 60);

        if ($hours >= 24) {
            $days = floor($hours / 24);
            $remainingHours = $hours % 24;
            return sprintf('%d %s %d %s', $days, __('days'), $remainingHours, __('hrs'));
        }

        return sprintf('%d %s %d %s', $hours, __('hrs'), $minutes, __('mins'));
    }
}
