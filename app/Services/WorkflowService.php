<?php

namespace App\Services;

use App\Models\ClientOrderSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WorkflowService
{
    // Define the workflow stages
    public const STAGES = [
        1 => [
            'id' => 1,
            'status' => SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW,
            'label' => 'Initial Check',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_INITIAL_EVALUATOR, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 2,
            'button_text' => 'Complete Initial Check',
            'auto_assign_role' => USER_ROLE_PEER_REVIEWER_MANAGER,
        ],
        2 => [
            'id' => 2,
            'status' => SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW,
            'label' => 'Peer Review',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_PEER_REVIEWER_MANAGER, USER_ROLE_REVIEWER, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 3,
            'button_text' => 'Complete Peer Review',
            'auto_assign_role' => USER_ROLE_ADMIN, // Editor-in-Chief
        ],
        3 => [
            'id' => 3,
            'status' => SUBMISSION_ORDER_STATUS_ACCEPTED, // Final Acceptance
            'label' => 'Final Acceptance - Decision',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 4,
            'button_text' => null, // Use specialized Editor Decision buttons
            'auto_assign_role' => USER_ROLE_TEAM_MEMBER, // Copyeditor usually
        ],
        4 => [
            'id' => 4,
            'status' => SUBMISSION_ORDER_STATUS_PROOFREADING,
            'label' => 'Proofreading & Metadata',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_TEAM_MEMBER, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 5,
            'button_text' => 'Complete Proofreading',
            'auto_assign_role' => USER_ROLE_FINANCIAL_MANAGER,
        ],
        5 => [
            'id' => 5,
            'status' => SUBMISSION_ORDER_STATUS_PAYMENT_APC,
            'label' => 'Payment (APC)',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_FINANCIAL_MANAGER, USER_ROLE_CLIENT, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 6,
            'button_text' => 'Confirm Payment', // Or Complete Payment
            'auto_assign_role' => USER_ROLE_ADMIN,
        ],
        6 => [
            'id' => 6,
            'status' => SUBMISSION_ORDER_STATUS_ACCEPTANCE_CERTIFICATE,
            'label' => 'Acceptance Certificate',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 7, // To Final Production
            'button_text' => 'Generate Certificate & Complete',
            'auto_assign_role' => USER_ROLE_PUBLISHER,
        ],
        7 => [
            'id' => 7,
            'status' => SUBMISSION_ORDER_STATUS_FINAL_PRODUCTION,
            'label' => 'Final Production',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_PUBLISHER, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 8,
            'button_text' => 'Complete Final Production',
            'auto_assign_role' => USER_ROLE_PUBLISHER,
        ],
        8 => [
            'id' => 8, // Publish Online
            'status' => SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
            'label' => 'Publish Online',
            'roles' => [USER_ROLE_ADMIN, USER_ROLE_PUBLISHER, USER_ROLE_SUPER_ADMIN],
            'next_stage' => 9,
            'button_text' => 'Publish Online',
            'auto_assign_role' => null,
        ],
        9 => [
            'id' => 9,
            'status' => SUBMISSION_ORDER_STATUS_PUBLISHED,
            'label' => 'Published',
            'roles' => [],
            'next_stage' => null,
            'button_text' => null,
            'auto_assign_role' => null,
        ],
    ];

    /**
     * Get the current stage details based on submission status.
     */
    public function getCurrentStage(string $status): ?array
    {
        foreach (self::STAGES as $stage) {
            if ($stage['status'] === $status) {
                return $stage;
            }
        }
        return null;
    }

    /**
     * Check if the user can complete the current stage.
     */
    public function canCompleteStage(ClientOrderSubmission $submission, User $user): bool
    {
        $currentStage = $this->getCurrentStage($submission->approval_status);

        if (!$currentStage) {
            return false;
        }

        // Special check for Payment stage: User (Author) can verify payment?
        // Usually system verifies payment, or Admin manually confirms it.
        // If Role is Client, maybe they can just "Complete" to notify admin?
        // But requirements say: "Payment (APC) (Author + Admin)"

        return in_array($user->role, $currentStage['roles']);
    }

    /**
     * Advance the submission to the next stage.
     */
    public function advanceStage(ClientOrderSubmission $submission): bool
    {
        $currentStage = $this->getCurrentStage($submission->approval_status);

        if (!$currentStage || !$currentStage['next_stage']) {
            return false;
        }

        $nextStage = self::STAGES[$currentStage['next_stage']];

        $submission->approval_status = $nextStage['status'];
        // You might want to update other fields like 'workflow_stage' if exists
        // $submission->workflow_stage = $nextStage['id'];
        $submission->save();

        // Auto-sync Order Task Status
        $this->syncOrderTaskStatus($submission, $nextStage['id']);

        // Log the change
        Log::info("Submission ID {$submission->id} advanced from {$currentStage['label']} to {$nextStage['label']} by User ID " . auth()->id());

        return true;
    }

    /**
     * Reject the submission.
     */
    public function rejectSubmission(ClientOrderSubmission $submission): bool
    {
        $submission->approval_status = SUBMISSION_ORDER_STATUS_PEER_REJECTED;
        $submission->save();

        // Lock Workflow: Update Task Status to Done (Rejected)
        // Or keep it in Review? Let's say DONE for now as it exits the active workflow.
        $this->syncOrderTaskStatus($submission, 'rejected');

        Log::info("Submission ID {$submission->id} rejected by User ID " . auth()->id());

        return true;
    }

    /**
     * Sync the Order Task status based on the workflow stage.
     */
    protected function syncOrderTaskStatus(ClientOrderSubmission $submission, $stageIdOrStatus)
    {
        // Default to Progress
        $taskStatus = ORDER_TASK_STATUS_PROGRESS;

        if ($stageIdOrStatus === 'rejected') {
            $taskStatus = ORDER_TASK_STATUS_DONE; // Or similar
        } elseif ($stageIdOrStatus === 3) { // Final Acceptance - Decision
            $taskStatus = ORDER_TASK_STATUS_REVIEW;
        } elseif ($stageIdOrStatus === 9) { // Published
            $taskStatus = ORDER_TASK_STATUS_DONE;
        }

        // Update all tasks related to this order? Or just the main one?
        // Assuming all tasks for now, as usually there is one main task board item.
        \App\Models\OrderTask::where('client_order_id', $submission->client_order_id)
            ->update(['status' => $taskStatus]);

        Log::info("Order Tasks for Order ID {$submission->client_order_id} synced to status {$taskStatus}");
    }
}
