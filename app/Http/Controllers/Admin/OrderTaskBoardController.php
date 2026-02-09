<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientOrderTaskBoardRequest;
use App\Models\ClientOrder;
use App\Models\FileManager;
use App\Models\Label;
use App\Models\ClientOrder as corder;
use App\Models\ClientOrderSubmission;
use App\Models\ClientOrderSubmissionRevision;
use App\Models\FinalCertificate;
use App\Models\OrderTask;
use App\Models\OrderTaskAssignee;
use App\Models\OrderTaskAttachment;
use App\Models\OrderTaskConversation;
use App\Models\OrderTaskConversationSeen;
use App\Models\PrimaryCertificate;
use App\Models\ReviewerCertificate;
use App\Models\Reviews;
use App\Models\SubmissionReviewerNotes;
use App\Models\User;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ArPHP\I18N\Arabic;


use App\Services\WorkflowService;

class OrderTaskBoardController extends Controller
{
    use ResponseTrait;

    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function list($order_id)
    {
        $data['pageTitle'] = __('Order Task');
        $data['activeClientOrderTaskBoardIndex'] = 'active';
        $data['activeOrder'] = 'active';

        $currentUser = auth()->user();
        if (!$currentUser) {
            abort(403);
        }

        if ($currentUser->role == USER_ROLE_CLIENT) {
            $clientOrder = ClientOrder::find($order_id);
            if (!$clientOrder instanceof ClientOrder) {
                abort(404);
            }
            return redirect()->route('user.orders.dashboard', $clientOrder->order_id);
        }

        $tenantId = $currentUser->tenant_id;
        $userId = $currentUser->id;
        $userRole = $currentUser->role;

        $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => $tenantId])->get();
        $data['reviewer'] = User::where(['role' => USER_ROLE_REVIEWER, 'tenant_id' => $tenantId])->get();
        $data['order'] = ClientOrder::with('submission_reviewer_notes')->where(['tenant_id' => $tenantId, 'id' => $order_id])->firstOrFail();
        $data['orderSubmission'] = ClientOrderSubmission::with('journal')->where('client_order_id', $data['order']->order_id)->first();
        $data['labels'] = Label::where(['tenant_id' => $tenantId])->get();

        $orderTasksQuery = OrderTask::where(['client_order_id' => $order_id, 'order_tasks.tenant_id' => $tenantId])
            ->with(['assignees.user', 'labels']);

        if ($userRole == USER_ROLE_CLIENT) {
            $orderTasksQuery->where('client_access', 1);
        } elseif ($userRole == USER_ROLE_TEAM_MEMBER) {
            $orderTasksQuery->join('order_task_assignees', 'order_tasks.id', '=', 'order_task_assignees.order_task_id')
                ->where('order_task_assignees.assign_to', $userId)
                ->whereNull('order_task_assignees.deleted_at')
                ->select('order_tasks.*');
        }

        $data['orderTasks'] = $orderTasksQuery->get();

        $assigneeList = [];
        if ($data['order'] != null) {
            foreach ($data['order']->assignee as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;


        // $date['submissionReviewerNotes'] = null;
        // if($data['orderSubmission']){
        //     $date['submissionReviewerNotes'] = SubmissionReviewerNotes::where('order_id', $data['order']->order_id)->first();
        // }

        $data['primaryCertificate'] = PrimaryCertificate::where('client_order_id', $data['order']->order_id)->first();

        $data['finalCertificate'] = FinalCertificate::where('client_order_id', $data['order']->order_id)->first();
        $data['reviewerCertificate'] = ReviewerCertificate::where('client_order_id', $data['order']->order_id)->where('reviewer_id', auth()->id())->first();

        //order submission and review
        $data['review'] = null;
        $data['reviewVersionHistory'] = null;
        if ($data['orderSubmission']) {
            $data['review'] = Reviews::where('client_order_submission_id', $data['orderSubmission']->id)->where('reviewer_id', auth()->id())->first();
            // Get full version history for editors/admins
            $data['reviewVersionHistory'] = Reviews::getVersionHistoryForSubmission($data['orderSubmission']->id);
            $data['revisionsByRound'] = ClientOrderSubmissionRevision::with(['attachments.file'])
                ->where('client_order_submission_id', $data['orderSubmission']->id)
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
                })
                ->groupBy(function ($revision) {
                    return $revision->version ?? 1;
                });
        } else {
            $data['revisionsByRound'] = collect();
        }

        $data['constStatus'] = [
            SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW,
            SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED,
            SUBMISSION_ORDER_STATUS_INITIAL_REJECTED,
            SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW,
            SUBMISSION_ORDER_STATUS_ACCEPTED,
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
            SUBMISSION_ORDER_STATUS_PEER_REJECTED,
            SUBMISSION_ORDER_STATUS_PROOFREADING,
            SUBMISSION_ORDER_STATUS_PAYMENT_APC,
            SUBMISSION_ORDER_STATUS_ACCEPTANCE_CERTIFICATE,
            SUBMISSION_ORDER_STATUS_FINAL_PRODUCTION,
            SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
            SUBMISSION_ORDER_STATUS_PUBLISHED,
        ];



        // Workflow Data
        $currentStage = $this->workflowService->getCurrentStage($data['orderSubmission']->approval_status);
        $canCompleteStage = false;
        if ($currentStage && $data['orderSubmission']) {
            $canCompleteStage = $this->workflowService->canCompleteStage($data['orderSubmission'], auth()->user());
        }
        $data['currentStage'] = $currentStage;
        $data['canCompleteStage'] = $canCompleteStage;

        return view('admin.orders.task-board.list', $data);
    }

    public function completeStage(Request $request, $submission_id)
    {
        try {
            $submission = ClientOrderSubmission::findOrFail(decrypt($submission_id));

            if (!$this->workflowService->canCompleteStage($submission, auth()->user())) {
                 return redirect()->back()->with('error', __('You are not authorized to complete this stage.'));
            }

            $this->workflowService->advanceStage($submission);

            return redirect()->back()->with('success', __('Stage completed successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    /**
     * Handle editor decision (Accept, Reject, Minor Revisions, Major Revisions)
     */
    public function editorDecision(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['authors', 'client_order.client', 'latestRevision'])->findOrFail($submissionId);
            $decision = $request->input('decision');

            $decisionMap = [
                'accept' => SUBMISSION_ORDER_STATUS_PROOFREADING,
                'minor_revisions' => SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
                'major_revisions' => SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
                'reject' => SUBMISSION_ORDER_STATUS_PEER_REJECTED,
                'request_revision' => SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
            ];

            if (!isset($decisionMap[$decision])) {
                return response()->json([
                    'success' => false,
                    'message' => __('Invalid decision')
                ], 400);
            }

            // Update submission status
            $submission->approval_status = $decisionMap[$decision];

            // Task 5 & 6: Handle acceptance - set acceptance fields and lock final manuscript
            if ($decision === 'accept') {
                $submission->acceptance_date = now();
                $submission->decision_by_user_id = auth()->id();
                $submission->metadata_status = 'pending_author'; // Trigger final metadata form

                // Task 6: Move to proofreading stage immediately after acceptance
                $submission->workflow_stage = 'proofreading';

                // Task 23-24: Assign to journal & issue if provided
                if ($request->has('journal_id') && $request->journal_id) {
                    $submission->journal_id = $request->journal_id;
                }
                if ($request->has('issue_id') && $request->issue_id) {
                    $submission->issue_id = $request->issue_id;
                    // If galley is ready, set status to scheduled
                    if ($submission->approval_status === 'galley_ready') {
                        $submission->approval_status = 'scheduled';
                        $issue = \App\Models\Issue::find($request->issue_id);
                        if ($issue && $issue->planned_publication_date) {
                            $submission->scheduled_publication_date = $issue->planned_publication_date;
                        }
                    }
                }

                // Lock final manuscript - get last approved revision (for proofreading stage)
                $latestRevision = $submission->latestRevision;
                if ($latestRevision && $latestRevision->manuscript_file_id) {
                    $submission->final_manuscript_file_id = $latestRevision->manuscript_file_id;
                } elseif ($submission->full_article_file) {
                    // Fallback to original submission file
                    $submission->final_manuscript_file_id = $submission->full_article_file;
                }

                // Automatically create initial proofreading version from the locked final manuscript
                // and move submission into the proofreading workflow state.
                if ($submission->final_manuscript_file_id) {
                    // Avoid duplicate auto-proof creation
                    $hasProofFiles = $submission->proofFiles()->exists();

                    if (!$hasProofFiles) {
                        // Version always starts from 1 for the first auto-created proof
                        \App\Models\ProofFile::create([
                            'client_order_submission_id' => $submission->id,
                            'file_id' => $submission->final_manuscript_file_id,
                            'version' => '1',
                            'notes' => __('Auto-created from the last submitted manuscript at acceptance.'),
                            'status' => 'pending',
                            'uploaded_by' => auth()->id(),
                            'review_type' => 'author',
                        ]);
                    }
                }

                // Move approval status to in_proofreading so the new stage is clearly reflected
                $submission->approval_status = 'in_proofreading';

                // Log activity
                if (function_exists('addUserActivityLog')) {
                    addUserActivityLog('Editor accepted paper: ' . ($submission->article_title ?? 'N/A'), auth()->user());
                }
            }

            $submission->save();
            // Log::info('Submissions: ' . $submission);
            // Send email to author(s)
            $brevoService = new BrevoService();
            $authorEmails = [];

            // Get author emails
            if ($submission->has_author && $submission->authors) {
                foreach ($submission->authors as $author) {
                    if (!empty($author->email)) {
                        $authorEmails[] = $author->email;
                    }
                }
            }
            // Log::info('Author Emails: ' . json_encode($authorEmails));
            // Fallback to client email if no authors
            if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                $authorEmails[] = $submission->client_order->client->email;
            }

            if (!empty($authorEmails)) {
                $emailSubject = $request->input('email_subject', __('Submission Decision'));
                $emailBody = $this->buildEmailBody($decision, $request, $submission);

                // Send email to all authors
                $emailResult = $brevoService->sendEmail(
                    implode(',', $authorEmails),
                    null,
                    $emailSubject,
                    $emailBody
                );

                if (!$emailResult['success']) {
                    Log::warning('Failed to send editor decision email: ' . ($emailResult['error'] ?? 'Unknown error'));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => true,
                'message' => __('Editor decision saved and email sent successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Editor decision error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => __('An error occurred while saving the decision: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build email body based on decision type
     */
    private function buildEmailBody($decision, $request, $submission)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';

        switch ($decision) {
            case 'accept':
                $body .= '<h2 style="color: #4CAF50;">' . __('Submission Accepted') . '</h2>';
                $body .= '<p>' . nl2br(e($request->input('email_message', __('Congratulations! Your submission has been accepted for publication.')))) . '</p>';
                if ($request->input('publication_schedule')) {
                    $body .= '<p><strong>' . __('Publication Schedule') . ':</strong> ' . e($request->input('publication_schedule')) . '</p>';
                }
                if ($request->input('issue_assignment')) {
                    $body .= '<p><strong>' . __('Assigned to Issue') . ':</strong> ' . e($request->input('issue_assignment')) . '</p>';
                }
                break;

            case 'minor_revisions':
            case 'major_revisions':
                $body .= '<h2 style="color: #FF9800;">' . ($decision === 'major_revisions' ? __('Major Revisions Required') : __('Minor Revisions Required')) . '</h2>';
                $body .= '<p>' . __('Your submission requires revisions before it can be accepted. Please review the following instructions carefully.') . '</p>';
                $body .= '<h3>' . __('Revision Instructions') . '</h3>';
                $body .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">';
                $body .= '<p>' . nl2br(e($request->input('revision_instructions', ''))) . '</p>';
                $body .= '</div>';
                if ($request->input('revision_deadline')) {
                    $deadline = \Carbon\Carbon::parse($request->input('revision_deadline'))->format('F j, Y');
                    $body .= '<p><strong>' . __('Revision Deadline') . ':</strong> ' . $deadline . '</p>';
                }
                if ($request->input('include_reviewer_comments')) {
                    $body .= '<hr><h3>' . __('Reviewer Comments') . '</h3>';
                    // Add compiled reviewer comments here if needed
                }
                break;

            case 'reject':
                $body .= '<h2 style="color: #f44336;">' . __('Submission Decision') . '</h2>';
                $body .= '<p>' . __('We regret to inform you that your submission has not been accepted for publication.') . '</p>';
                if ($request->input('rejection_reason')) {
                    $body .= '<p><strong>' . __('Reason') . ':</strong> ' . e($request->input('rejection_reason')) . '</p>';
                }
                $body .= '<h3>' . __('Feedback') . '</h3>';
                $body .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">';
                $body .= '<p>' . nl2br(e($request->input('rejection_message', ''))) . '</p>';
                $body .= '</div>';
                break;

            case 'request_revision':
                $body .= '<h2 style="color: #2196F3;">' . __('Additional Revisions Required') . '</h2>';
                $body .= '<p>' . __('Your submission requires additional revisions. Please review the following instructions.') . '</p>';
                $body .= '<h3>' . __('Revision Instructions') . '</h3>';
                $body .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">';
                $body .= '<p>' . nl2br(e($request->input('revision_instructions', ''))) . '</p>';
                $body .= '</div>';
                if ($request->input('revision_deadline')) {
                    $deadline = \Carbon\Carbon::parse($request->input('revision_deadline'))->format('F j, Y');
                    $body .= '<p><strong>' . __('Revision Deadline') . ':</strong> ' . $deadline . '</p>';
                }
                break;
        }

        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';

        return $body;
    }

    /**
     * Request another revision round (increments round and allows Version 3, 4, etc.)
     */
    public function requestRevision(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['authors', 'client_order.client'])->findOrFail($submissionId);

            // Get current round and increment
            $currentRound = Reviews::getCurrentRound($submissionId);
            $nextRound = $currentRound + 1;

            // Update submission status to indicate revision requested
            $submission->approval_status = SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS;
            $submission->save();

            // Get all reviewers who have reviewed this submission
            $reviewers = Reviews::where('client_order_submission_id', $submissionId)
                ->distinct()
                ->pluck('reviewer_id');

            foreach ($reviewers as $reviewerId) {
                // Check if a review for this round already exists
                $existingReview = Reviews::where('client_order_submission_id', $submissionId)
                    ->where('reviewer_id', $reviewerId)
                    ->where('round', $nextRound)
                    ->first();

                if (!$existingReview) {
                    // Get the latest version for this reviewer
                    $latestReview = Reviews::where('client_order_submission_id', $submissionId)
                        ->where('reviewer_id', $reviewerId)
                        ->orderByDesc('version')
                        ->orderByDesc('round')
                        ->first();

                    if ($latestReview) {
                        // Create a new review for the next round (will be version 1 of the new round)
                        $newReview = new Reviews();
                        $newReview->client_order_submission_id = $submissionId;
                        $newReview->client_order_id = $submission->client_order_id;
                        $newReview->reviewer_id = $reviewerId;
                        $newReview->version = 1; // Start at version 1 for new round
                        $newReview->round = $nextRound;
                        $newReview->previous_version_id = $latestReview->id;
                        $newReview->status = SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW;
                        $newReview->invitation_status = 'accepted';
                        $newReview->progress = 0;
                        $newReview->save();
                    }
                }
            }

            // Send email to author(s)
            $brevoService = new BrevoService();
            $authorEmails = [];

            // Get author emails
            if ($submission->has_author && $submission->authors) {
                foreach ($submission->authors as $author) {
                    if (!empty($author->email)) {
                        $authorEmails[] = $author->email;
                    }
                }
            }

            // Fallback to client email if no authors
            if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                $authorEmails[] = $submission->client_order->client->email;
            }

            if (!empty($authorEmails)) {
                $emailSubject = $request->input('email_subject', __('Additional Revisions Required'));
                $emailBody = $this->buildEmailBody('request_revision', $request, $submission);

                // Send email to all authors
                $emailResult = $brevoService->sendEmail(
                    implode(',', $authorEmails),
                    null,
                    $emailSubject,
                    $emailBody
                );

                if (!$emailResult['success']) {
                    Log::warning('Failed to send revision request email: ' . ($emailResult['error'] ?? 'Unknown error'));
                }
            } else {
                Log::warning('No author emails found for submission: ' . $submissionId);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => true,
                'message' => __('Another revision round has been requested. Reviewers will be able to submit Version ' . ($currentRound + 1) . ' reviews.'),
                'next_round' => $nextRound
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Request revision error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while requesting revision')
            ], 500);
        }
    }

    // added By oubtou
    public function uploadfile(Request $request)
    {
        if ($request->id && $request->file) {
            $clientOrder = corder::find($request->id);
            if (!$clientOrder instanceof corder) {
                abort(404);
            }
            $file = FileManager::where('id', $clientOrder->file)->first();
            if ($file) {
                $file->removeFile();
                $uploaded = $file->upload('Order', $request->file, '', $file->id);
            } else {
                $file = new FileManager();
                $uploaded = $file->upload('Service', $request->file);
            }

            $clientOrder->file = $uploaded->id;
            $clientOrder->save();
        }

        return redirect()->back();
    }
    // By oubtou

    public function store(ClientOrderTaskBoardRequest $request, $order_id, $id = null)
    {
        try {
            DB::beginTransaction();

            // Determine if this is a create or update operation
            $orderTask = $id ? OrderTask::find($id) : new OrderTask;
            $isUpdate = $id ? true : false;

            // Set common attributes
            $orderTask->task_name = $request->task_name;
            $orderTask->client_order_id = $order_id;
            $orderTask->tenant_id = auth()->user()->tenant_id;
            $orderTask->description = $request->description;
            $orderTask->start_date = $request->start_date;
            $orderTask->end_date = $request->end_date;
            $orderTask->priority = $request->priority;
            $orderTask->client_access = $request->has_client_access ? 1 : 0;
            $orderTask->created_by = $isUpdate ? $orderTask->created_by : auth()->id();
            $orderTask->status = $request->status;

            // Save or update the task
            $orderTask->save();

            // Generate a unique taskBoard ID if this is a new task
            if (!$isUpdate) {
                $orderTask->taskId = generateUniqueTaskboardId($orderTask->id);
                $orderTask->save();
            }

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('attachments', $file);

                    if (!is_null($uploaded)) {
                        $orderTask->attachments()->create([
                            'file' => $uploaded->id,
                            'order_task_id' => $orderTask->id,
                        ]);
                    } else {
                        DB::rollBack();
                        return $this->error([], __('Something went wrong with the file upload.'));
                    }
                }
            }

            // Extract label names from the request
            $labelNames = $request->labels;

            // Find or create labels and collect their IDs
            $labelIds = collect($labelNames)->map(function ($labelName) {
                $label = Label::firstOrCreate(['name' => $labelName], ['tenant_id' => auth()->user()->tenant_id]);
                return $label->id;
            });

            // Sync the label IDs to the orderTask->labels relationship
            $orderTask->labels()->sync($labelIds);

            // Handle assignees
            if ($request->assign_member) {
                $assignMemberIds = $request->assign_member;

                // Get current assignees
                $currentAssignees = $orderTask->assignees->pluck('assign_to')->toArray();

                // Determine assignees to delete and to add
                $assigneesToDelete = array_diff($currentAssignees, $assignMemberIds);
                $assigneesToAdd = array_diff($assignMemberIds, $currentAssignees);

                // Delete the assignees that are no longer assigned
                OrderTaskAssignee::where('order_task_id', $orderTask->id)
                    ->whereIn('assign_to', $assigneesToDelete)
                    ->delete();

                // Add the new assignees
                foreach ($assigneesToAdd as $userId) {
                    OrderTaskAssignee::create([
                        'tenant_id' => auth()->user()->tenant_id,
                        'order_task_id' => $orderTask->id,
                        'assign_to' => $userId,
                        'assign_by' => auth()->id(),
                    ]);

                    //sent mail here for every assign user
                    try {
                        orderTaskAssigneMemberEmailNotify($orderTask, $userId);
                    } catch (Exception $e) {
                        return $this->error([], __('Something went wrong, please try again'));
                    }
                }
            }

            DB::commit();
            return $this->success([], $isUpdate ? __('Updated Successfully') : __('Added Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong, please try again'));
        }
    }

    public function updateStatus(Request $request, $order_id)
    {
        try {
            $task = OrderTask::where('id', $request->task_id)
                ->where('client_order_id', $order_id)
                ->first();

            $task->status = $request->new_status;
            $task->save();
            return $this->success();
        } catch (\Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }

    }

    public function edit($order_id, $id)
    {
        $data['orderTask'] = OrderTask::where('id', $id)
            ->where('client_order_id', $order_id)
            ->with(['assignees.user', 'labels', 'order'])
            ->first();

        $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        $data['labels'] = Label::where(['tenant_id' => auth()->user()->tenant_id])->get();
        $data['order'] = $data['orderTask']->order;

        $assigneeList = [];
        if ($data['order'] != null) {
            foreach ($data['order']->assignee as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;

        return view('admin.orders.task-board.edit', $data)->render();
    }

    public function delete($order_id, $id)
    {
        try {
            OrderTask::where('id', $id)
                ->where('client_order_id', $order_id)
                ->delete();
            return $this->success([], __('Deleted Successfully'));
        } catch (\Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function view($order_id, $id)
    {
        $data['orderTask'] = OrderTask::where('id', $id)
            ->where('client_order_id', $order_id)
            ->with(['assignees.user', 'labels', 'order', 'attachments.filemanager'])
            ->first();

        $data['order'] = $data['orderTask']->order;
        $data['conversationClientTypeData'] = OrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_CLIENT])->with('user')->get();
        $data['conversationTeamTypeData'] = OrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_TEAM])->with('user')->get();

        return view('admin.orders.task-board.view', $data)->render();
    }

    public function primary_certificate($order_id)
    {
        //For Arabic support
        $Arabic = new Arabic();
        $language = selectedLanguage();
        $isLanguageArabic = false;
        if ($language->iso_code == 'ar') {
            $isLanguageArabic = true;
        }

        $clientOrder = ClientOrder::where('id', decrypt($order_id))->first();
        $data['primaryCertificate'] = PrimaryCertificate::where('client_order_id', $clientOrder->order_id)->first();
        if (!$data['primaryCertificate']) {
            return back()->with('error', 'Certificate not found');
        }
        $data = [
            'author' => $data['primaryCertificate']->author_names,
            'affiliation' => $data['primaryCertificate']->author_affiliations,
            'paper_title' => $data['primaryCertificate']->paper_title,
            'journal_name' => $data['primaryCertificate']->journal_name,
            'order_id' => $clientOrder->order_id,
            'date' => $data['primaryCertificate']->created_at->format('d/m/Y'),
            'ref_no' => $clientOrder->order_id,
            'signature' => 'Dr. Jane Smith',
        ];

        //dynamic data for Arabic support
        foreach ($data as $key => $value) {
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                $data[$key] = $Arabic->utf8Glyphs($value);
            }
        }

        // For Arabic support
        if ($isLanguageArabic) {
            $staticData = [
                'headerOne' => 'شهادة القبول الأولي',
                'paraOne' => 'يشهد هذا بأن',
                'paraTwo' => 'من',
                'paraThree' => 'قد قدم الورقة بعنوان',
                'journalInfoParaOne' => 'للنظر في',
                'signaturePara' => 'رئيس هيئة التحرير',
            ];

            foreach ($staticData as $key => $value) {
                $data[$key . 'Static'] = $Arabic->utf8Glyphs($value);
            }
        }

        $pdf = Pdf::loadView('admin.certificate.primary.details', $data);
        $pdf->setPaper('A4', 'portrait');

         // For better Arabic support
        $pdf->setOption('defaultFont', 'arabicfont');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->stream('certificate.pdf');
    }

    public function final_certificate($order_id)
    {
        //For Arabic support
        $Arabic = new Arabic();
        $language = selectedLanguage();
        $isLanguageArabic = false;
        if ($language->iso_code == 'ar') {
            $isLanguageArabic = true;
        }

        $clientOrder = ClientOrder::where('id', decrypt($order_id))->first();
        $data['finalCertificate'] = FinalCertificate::where('client_order_id', $clientOrder->order_id)->first();
        if (!$data['finalCertificate']) {
            return back()->with('error', 'Certificate not found');
        }
        $data = [
            'author' => $data['finalCertificate']->author_names,
            'affiliation' => $data['finalCertificate']->author_affiliations,
            'paper_title' => $data['finalCertificate']->paper_title,
            'journal_name' => $data['finalCertificate']->journal_name,
            'volume' => $data['finalCertificate']->volume,
            'issue' => $data['finalCertificate']->issue,
            'date' => $data['finalCertificate']->date,
            'order_id' => $clientOrder->order_id,
            'ref_no' => $clientOrder->order_id,
            'signature' => 'Dr. Jane Smith',
        ];

        //dynamic data for Arabic support
        foreach ($data as $key => $value) {
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                $data[$key] = $Arabic->utf8Glyphs($value);
            }
        }

        // For Arabic support
        if ($isLanguageArabic) {
            $staticData = [
                'headerOne' => 'شهادة القبول النهائي',
                'paraOne' => 'هذه الشهادة تمنح إلى',
                'paraTwo' => 'من',
                'paraThree' => 'لنشر البحث العلمي المعنون:',
                'journalInfoParaOne' => 'نشر في',
                'journalInfoParaTwo' => 'المجلد',
                'journalInfoParaThree' => 'العدد',
                'journalInfoParaFour' => 'التاريخ',
                'signaturePara' => 'رئيس هيئة التحرير',
            ];

            foreach ($staticData as $key => $value) {
                $data[$key . 'Static'] = $Arabic->utf8Glyphs($value);
            }
        }

        $pdf = Pdf::loadView('admin.certificate.final.details', $data);
        $pdf->setPaper('A4', 'portrait');

        // For better Arabic support
        $pdf->setOption('defaultFont', 'arabicfont');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->stream('certificate.pdf');
    }

    public function reviewer_certificate($order_id)
    {

        //For Arabic support
        $Arabic = new Arabic();
        $language = selectedLanguage();
        $isLanguageArabic = false;
        if ($language->iso_code == 'ar') {
            $isLanguageArabic = true;
        }

        $clientOrder = ClientOrder::where('id', decrypt($order_id))->first();
        $data['reviewerCertificate'] = ReviewerCertificate::where('client_order_id', $clientOrder->order_id)->where('reviewer_id', auth()->user()->id)->first();
        if (!$data['reviewerCertificate']) {
            return back()->with('error', 'Certificate not found');
        }
        $data = [
            'title' => $data['reviewerCertificate']->title,
            'reviewer_name' => $data['reviewerCertificate']->reviewer->name,
            'affiliations' => $data['reviewerCertificate']->affiliations,
            'paper_title' => $data['reviewerCertificate']->paper_title,
            'journal_name' => $data['reviewerCertificate']->journal_name,
            'order_id' => $clientOrder->order_id,
            'date' => \Carbon\Carbon::now()->format('d/m/Y'),
            'ref_no' => 'AJSRP/' . $clientOrder->order_id . '/' . \Carbon\Carbon::now()->format('Y'),
            'signature' => 'Dr. Jane Smith',
        ];

        //dynamic data for Arabic support
        foreach ($data as $key => $value) {
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                $data[$key] = $Arabic->utf8Glyphs($value);
            }
        }

        // For Arabic support
        if ($isLanguageArabic) {
            $staticData = [
                'headerOne' => 'شهادة شكر للمحكم',
                'paraOne' => 'نقدّر ونشكر',
                'paraTwo' => 'من',
                'paraThree' => 'لقيامه بمراجعة المخطوطة المعنونة',
                'journalInfoParaOne' => 'المقدمة إلى',
                'signaturePara' => 'رئيس هيئة التحرير',
            ];

            foreach ($staticData as $key => $value) {
                $data[$key . 'Static'] = $Arabic->utf8Glyphs($value);
            }
        }

        $pdf = Pdf::loadView('admin.certificate.reviewer.details', $data);
        $pdf->setPaper('A4', 'portrait');


         // For better Arabic support
        $pdf->setOption('defaultFont', 'arabicfont');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->stream('certificate.pdf');
    }

    public function deleteAttachment($order_id, $task_id, $id)
    {
        OrderTaskAttachment::where(['order_task_id' => $task_id, 'file' => $id])->delete();
        return $this->success([], __('Deleted Successfully'));
    }

    public function changeProgress(Request $request, $order_id, $id)
    {
        OrderTask::where(['client_order_id' => $order_id, 'id' => $id])->update(['progress' => $request->progress]);
        return $this->success([], __('Progress Update Successfully'));
    }

    public function conversationStore(Request $request, $order_id, $id)
    {
        $request->validate([
            'conversation_text' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $dataObj = new OrderTaskConversation();
            $dataObj->tenant_id = auth()->user()->tenant_id;
            $dataObj->order_task_id = $id;
            $dataObj->conversation_text = $request->conversation_text;
            $dataObj->type = $request->type;
            $dataObj->user_id = auth()->id();

            /*File Manager Call upload*/
            if ($request->file) {
                $fileId = [];
                foreach ($request->file as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('ticket-conversation-documents', $singlefile);
                    array_push($fileId, $uploaded->id);
                }
                $dataObj->attachment = json_encode($fileId);
            }
            /*File Manager Call upload*/

            $dataObj->save();
            DB::commit();

            $renderData['conversationClientTypeData'] = OrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_CLIENT])->with('user')->get();
            $renderData['conversationTeamTypeData'] = OrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_TEAM])->with('user')->get();
            $renderData['type'] = $request->type;

            if (auth()->user()->role == USER_ROLE_CLIENT) {
                $data['conversationClientTypeData'] = view('user.orders.task-board.conversation_list_render', $renderData)->render();
            } else {
                $data['conversationClientTypeData'] = view('user.orders.task-board.conversation_list_render', $renderData)->render();
                $data['conversationTeamTypeData'] = view('admin.orders.task-board.conversation_list_render', $renderData)->render();
            }
            $data['type'] = $request->type;

            OrderTaskConversationSeen::where('order_task_id', $id)
                ->where('created_by', '!=', auth()->id())
                ->update(['is_seen' => 0]);

            OrderTask::where(['id' => $id, 'tenant_id' => auth()->user()->tenant_id])
                ->update([
                    'last_reply_id' => $dataObj->id,
                    'last_reply_by' => auth()->id(),
                    'last_reply_time' => now(),
                ]);

            return $this->success($data, __(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

}






