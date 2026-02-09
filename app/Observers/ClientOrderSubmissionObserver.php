<?php

namespace App\Observers;

use App\Models\ClientOrderSubmission;
use App\Models\ClientOrderSubmissionWorkflowHistory;
use Illuminate\Support\Facades\Schema;

class ClientOrderSubmissionObserver
{
    public function updating(ClientOrderSubmission $submission): void
    {
        // Auto-mark as published once publication_date is set
        if (
            $submission->isDirty('publication_date') &&
            !empty($submission->publication_date) &&
            $submission->approval_status !== SUBMISSION_ORDER_STATUS_PUBLISHED
        ) {
            $submission->approval_status = SUBMISSION_ORDER_STATUS_PUBLISHED;
        }
    }

    public function updated(ClientOrderSubmission $submission): void
    {
        $actorId = null;
        try {
            if (auth()->check()) {
                $actorId = auth()->id();
            }
        } catch (\Throwable $e) {
            $actorId = null;
        }

        // Avoid runtime errors if migration hasn't been applied yet.
        try {
            if (!Schema::hasTable('client_order_submission_workflow_histories')) {
                return;
            }
        } catch (\Throwable $e) {
            return;
        }

        $trackedFields = [
            'approval_status' => 'approval_status_changed',
            'workflow_stage' => 'workflow_stage_changed',
            'metadata_status' => 'metadata_status_changed',
            'publication_date' => 'publication_date_changed',
            'scheduled_publication_date' => 'scheduled_publication_date_changed',
        ];

        foreach ($trackedFields as $field => $eventType) {
            if (!$submission->wasChanged($field)) {
                continue;
            }

            $from = $submission->getOriginal($field);
            $to = $submission->getAttribute($field);

            try {
                ClientOrderSubmissionWorkflowHistory::create([
                    'client_order_submission_id' => $submission->id,
                    'event_type' => $eventType,
                    'field' => $field,
                    'from_value' => is_scalar($from) || is_null($from) ? (string) $from : json_encode($from),
                    'to_value' => is_scalar($to) || is_null($to) ? (string) $to : json_encode($to),
                    'meta' => [
                        'client_order_id' => $submission->client_order_id,
                        'journal_id' => $submission->journal_id,
                    ],
                    'actor_id' => $actorId,
                ]);
            } catch (\Throwable $e) {
                // Never break business flow due to audit logging
            }
        }
    }
}


