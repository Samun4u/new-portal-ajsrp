<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderSubmission;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewerInvitationController extends Controller
{
    public function show(string $token)
    {
        $review = Reviews::with([
            'reviewer',
            'client_order_submission.journal',
            'client_order_submission.article_type',
        ])->where('invitation_metadata->token', $token)->firstOrFail();

        $submission = $review->client_order_submission;
        $order = ClientOrder::where('order_id', $review->client_order_id)
            ->with('client')
            ->first();

        $assignment = ClientOrderAssignee::where('order_id', optional($order)->id)
            ->where('assigned_to', $review->reviewer_id)
            ->first();

        return view('frontend.reviewer.invitation', [
            'review' => $review,
            'submission' => $submission,
            'order' => $order,
            'assignment' => $assignment,
            'token' => $token,
            'canRespond' => $review->invitation_status === 'pending',
        ]);
    }

    public function respond(Request $request, string $token)
    {
        $review = Reviews::where('invitation_metadata->token', $token)->firstOrFail();

        if ($review->invitation_status !== 'pending') {
            return redirect()
                ->route('reviewer.invitation.show', $token)
                ->with('status', __('This invitation has already been answered.'));
        }

        $request->validate([
            'decision' => 'required|in:accept,decline',
            'conflict_declared' => 'required|boolean',
            'conflict_details' => 'nullable|string|max:2000',
        ]);

        $conflictDeclared = (bool) $request->boolean('conflict_declared');

        if ($conflictDeclared && empty($request->conflict_details)) {
            return back()->withErrors([
                'conflict_details' => __('Please provide details about the potential conflict of interest.'),
            ])->withInput();
        }

        $order = ClientOrder::where('order_id', $review->client_order_id)->first();
        $assignment = ClientOrderAssignee::where('order_id', optional($order)->id)
            ->where('assigned_to', $review->reviewer_id)
            ->first();

        DB::beginTransaction();

        try {
            $decision = $request->input('decision');

            $review->invitation_status = $decision === 'accept' ? 'accepted' : 'declined';
            $review->responded_at = now();
            $review->conflict_declared = $conflictDeclared;
            $review->conflict_details = $conflictDeclared ? $request->conflict_details : null;

            $metadata = $review->invitation_metadata ?? [];
            $metadata['response_source'] = 'web';
            $metadata['responded_at'] = now()->toIso8601String();

            $review->invitation_metadata = $metadata;
            $review->status = $decision === 'accept'
                ? SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS
                : SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW;
            $review->save();

            if ($assignment) {
                $assignment->invitation_status = $review->invitation_status;
                $assignment->responded_at = now();

                if ($decision === 'decline') {
                    $assignment->is_active = false;
                }

                $assignment->save();
            }

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Reviewer invitation response error', [
                'token' => $token,
                'message' => $throwable->getMessage(),
            ]);

            return redirect()
                ->route('reviewer.invitation.show', $token)
                ->withErrors(['decision' => __('We could not record your response. Please try again.')]);
        }

        $message = $decision === 'accept'
            ? __('Thank you for accepting the review invitation. You can now access the manuscript from your reviewer dashboard.')
            : __('Thank you for letting us know. We will assign this manuscript to another reviewer.');

        if ($order && $order->client) {
            setCommonNotification(
                $order->client->id,
                __('Reviewer invitation response'),
                __(':reviewer has responded to the review invitation (:decision).', [
                    'reviewer' => optional($review->reviewer)->name ?? __('Reviewer'),
                    'decision' => $decision === 'accept' ? __('accepted') : __('declined'),
                ]),
                ''
            );
        }

        return redirect()
            ->route('reviewer.invitation.show', $token)
            ->with('success', $message);
    }
}

