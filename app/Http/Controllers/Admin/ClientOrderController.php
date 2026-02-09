<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientOrderRequest;
use App\Http\Requests\User\ClientOrderConversationRequest;
use App\Http\Services\ClientInvoiceServices;
use App\Http\Services\ClientOrderServices;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderConversation;
use App\Models\ClientOrderConversationSeen;
use App\Models\ClientOrderItem;
use App\Models\ClientOrderNote;
use App\Models\ClientOrderSubmission;
use App\Models\OrderAssignee;
use App\Models\PrimaryCertificate;
use App\Models\ReviewerCertificate;
use App\Models\Reviews;
use App\Models\Service;
use App\Models\TicketAssignee;
use App\Models\TicketSeenUnseen;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReviewerInvitation;

class ClientOrderController extends Controller
{
    use ResponseTrait;

    public $clientOrderService;

    public function __construct()
    {
        $this->clientOrderService = new ClientOrderServices();
    }

    public function list(Request $request)
    {
        $data['pageTitle'] = __('Client Order list');
        $data['activeClientOrderIndex'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getClientOrderListData($request);
        }
        return view('admin.orders.list', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Client Order Add List');
        $data['activeClientOrderIndex'] = 'active';
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        $data['allService'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();

        return view('admin.orders.add',$data);
    }

    public function getService()
    {
        $data = $this->clientOrderService->getInvoice();
        return $this->success($data);
    }

    public function store(ClientOrderRequest $request)
    {
        return $this->clientOrderService->store($request);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Edit Order');
        $data['activeClientOrderIndex'] = 'active';
        $data['order'] = ClientOrder::with('client')->find($id);
        $data['orderItem'] = ClientOrderItem::where('order_id', $id)->get();
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        $data['allService'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();
        return view('admin.orders.edit', $data);
    }

    public function delete($id)
    {
        return $this->clientOrderService->delete($id);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Order Details');
        $data['activeClientOrderIndex'] = 'active';
        $data['orderDetails'] = ClientOrder::where('id', decrypt($id))->with(['client_order_items','assignee','notes'])->first();
        $data['conversationClientTypeData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_CLIENT])->get();
        $data['conversationTeamTypeData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_TEAM])->get();
        $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();

        $assigneeList = [];
        if($data['orderDetails'] != null) {
            foreach ($data['orderDetails']->assignee as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;

        $seenUneenData = ClientOrderConversationSeen::where(['order_id'=>decrypt($id), 'created_by'=>auth()->id()])->first();
        if(is_null($seenUneenData)){
            $seenUneenData = new ClientOrderConversationSeen();
            $seenUneenData->order_id = decrypt($id);
            $seenUneenData->created_by = auth()->id();
            $seenUneenData->is_seen = 1;
            $seenUneenData->tenant_id = auth()->user()->tenant_id;
        }else{
            $seenUneenData->is_seen = 1;
        }
        $seenUneenData->save();

        return view('admin.orders.details',$data);
    }
    public function conversationStore(ClientOrderConversationRequest $request)
    {
        return $this->clientOrderService->conversationStore($request);
    }

    public function statusChange($order_id, $status){
        DB::beginTransaction();
        try {
            $data = ClientOrder::find(decrypt($order_id));

            //check has any submission orders if has then check has any reviewer assigned if has assigned then create   or update  reviews
            // $clientOrderSubmission   = ClientOrderSubmission::where('client_order_id', $data->order_id)->first();
            // if($clientOrderSubmission){
            //     $assignedIds  = ClientOrderAssignee::whhere('order_id',$data->id)->pluck('assigned_to')->toArray();
            //     if(count($assignedIds) > 0){
            //         $assignedReviewerIds =    User::whereIn('id', $assignedIds)->where('role', USER_ROLE_REVIEWER)->pluck('id')->toArray();

            //         if(count($assignedReviewerIds) > 0){
            //             foreach($assignedReviewerIds as $reviewerId){
            //                 $reviewData = Reviews::where('client_order_submission_id', $clientOrderSubmission->id)->where('reviewer_id', $reviewerId)->first();
            //                 if($reviewData){

            //                 }else{
            //                     $reviewStore = new Reviews();
            //                     $reviewStore->client_order_submission_id = $clientOrderSubmission->id;
            //                     $reviewStore->client_order_id = $clientOrderSubmission->client_order_id;
            //                     $reviewStore->reviewer_id = $reviewerId;
            //                     $reviewStore->status = $status;
            //                     $reviewStore->save();
            //                 }

            //             }
            //         }
            //     }
            // }


            $data->working_status = $status;
            $data->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'Status Change successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => SOMETHING_WENT_WRONG]);
        }
    }
    public function submissionStatusChange(Request $request){

        $order_id = $request->order_id;
        $status = $request->status;

        try {

            $clientOrder = ClientOrder::with('client')->find(decrypt($order_id));
            $clientOrderSubmission = ClientOrderSubmission::with('journal')->where('client_order_id', $clientOrder->order_id)->first();

            $returnMsg = '';

            if($status == $clientOrderSubmission->approval_status){
                $returnMsg = 'Submission label status already updated';
                return redirect()->back()->with(['error' => $returnMsg]);
            }

            if($status == SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED){
                if($clientOrderSubmission->approval_status != SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW){
                    $errorMsg = 'Submission status is not under primary review';
                    return redirect()->back()->with(['error' => $errorMsg]);
                }

                $returnMsg = 'Submission label status update successfully';

                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
            }else if($status == SUBMISSION_ORDER_STATUS_INITIAL_REJECTED){

                if(
                    $clientOrderSubmission->approval_status != SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW
                ){
                    $errorMsg = 'Submission status is not under primary review';
                    return redirect()->back()->with(['error' => $errorMsg]);
                }

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
                submissionOrderRejectedEmailNotify($clientOrder->client_id);
            }else if($status == SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW){

                // if(
                //     $clientOrder->payment_status != PAYMENT_STATUS_PAID
                // ){
                //     $errorMsg = 'Payment is not completed';
                //     return redirect()->back()->with(['error' => $errorMsg]);
                // }

                //create or get and send primary certificate start =============================================================
                $primaryCertificate = PrimaryCertificate::where('client_order_id', $clientOrderSubmission->client_order_id)->first();
                if(!$primaryCertificate){
                    $authors = $clientOrderSubmission->authors;

                    // $affiliations = [];
                    // foreach($authors as $author){
                    //     $affiliations = array_merge($affiliations, json_decode($author->affiliation, true));
                    // }
                    // $affiliations = array_unique($affiliations);
                    // $affiliations = implode(', ', $affiliations);

                    $affiliationGroups = [];

                    foreach ($authors as $author) {
                        $decoded = json_decode($author->affiliation, true);

                        if (is_array($decoded)) {
                            if (isset($decoded[0]) && is_string($decoded[0])) {
                                $affiliationGroups[] = implode(', ', $decoded);
                            }
                            elseif (isset($decoded[0]) && is_array($decoded[0])) {
                                foreach ($decoded as $item) {
                                    $affiliationGroups[] = implode(', ', array_values($item));
                                }
                            }
                        }
                    }


                    if (count($affiliationGroups) > 1) {
                        $affiliations = implode(' | ', $affiliationGroups);
                    } else {
                        $affiliations = implode('', $affiliationGroups);
                    }



                    $authors = $authors->map(function ($author) {
                        return $author->first_name . ' ' . $author->last_name;
                    });

                    $authors = implode(', ', $authors->toArray());

                    //journal title
                    $journal = $clientOrderSubmission->journal;
                    $journal = $journal->title;

                    $paper_title = $clientOrderSubmission->article_title;

                    $primaryCertificate = new PrimaryCertificate();
                    $primaryCertificate->client_order_id = $clientOrderSubmission->client_order_id;
                    $primaryCertificate->author_names = $authors;
                    $primaryCertificate->author_affiliations = $affiliations;
                    $primaryCertificate->journal_name = $journal;
                    $primaryCertificate->paper_title = $paper_title;
                    $primaryCertificate->save();
                }

                // DISABLED: Certificates will be sent only after user submits authors-form and admin approves
                // primaryCertificateEmailNotifyForCustomer($primaryCertificate->client_order_id);
                // PrimaryCertificateNotifyForCustomer($primaryCertificate->client_order_id);

                // Notify user to submit the authors-form
                notifyUserToSubmitAuthorsForm($clientOrder->client_id, $primaryCertificate->client_order_id);
                //create or get and send primary certificate end =============================================================

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
                //submissionOrderCompletedEmailNotify($clientOrder->client_id);
            }else if($status == SUBMISSION_ORDER_STATUS_ACCEPTED){

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
            }else if($status == SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS){

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
            }else if($status == SUBMISSION_ORDER_STATUS_PEER_REJECTED){

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
            }else if ($status == SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION) {

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();

                // Create and send invoice if not already created or if unpaid
                $existingInvoice = ClientInvoice::where('order_id', $clientOrder->id)
                    ->where('tenant_id', auth()->user()->tenant_id)
                    ->latest()
                    ->first();

                if (!$existingInvoice || $existingInvoice->payment_status == PAYMENT_STATUS_PENDING) {
                    // Create new invoice or use existing unpaid one
                    if (!$existingInvoice) {
                        $todayDate = Carbon::now()->format('Y-m-d');

                        $invoice = new ClientInvoice();
                        $invoice->client_id = $clientOrder->client_id;
                        $invoice->due_date = $todayDate;
                        $invoice->tenant_id = auth()->user()->tenant_id;
                        $invoice->total = $clientOrder->total;
                        $invoice->order_id = $clientOrder->id;
                        $invoice->payable_amount = $clientOrder->total;
                        $invoice->payment_status = PAYMENT_STATUS_PENDING;
                        $clientOrder->payment_status = PAYMENT_STATUS_PENDING;
                        $clientOrder->save();

                        $invoice->transaction_id = uniqid();
                        $invoice->create_type = CREATE_TYPE_ACTIVE;
                        $invoice->save();
                        $invoice->invoice_id = 'INV-' . sprintf('%06d', $invoice->id);
                        $invoice->save();

                        // Send invoice email to author
                        submissionOrderAcceptedEmailNotify($invoice);
                    } else {
                        // Resend invoice email for existing unpaid invoice
                        submissionOrderAcceptedEmailNotify($existingInvoice);
                    }
                }

                //Thank you email notify to all thank you certificates created reviewers
                $reviewerCertificates = ReviewerCertificate::where('client_order_id', $clientOrderSubmission->client_order_id)->get();
                if(!empty($reviewerCertificates)){

                    foreach ($reviewerCertificates as $reviewerCertificate) {
                        $userData = User::where('id', $reviewerCertificate->reviewer_id)->first();
                        reviewerCertificateEmailNotifyForCustomer($reviewerCertificate->client_order_id, $userData);
                        ReviewerCertificateNotifyForCustomer($reviewerCertificate->client_order_id, $userData);
                    }
                }

                //Accept for publication email notify to author
                articleAcceptedForPublicationEmailNotify($clientOrderSubmission->id, $clientOrder->client_id);


            }else if ($status == SUBMISSION_ORDER_STATUS_PUBLISHED) {

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
                //submissionOrderCompletedEmailNotify($clientOrder->client_id);

            }else if ($status == SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW) {

                $returnMsg = 'Submission label status update successfully';
                $clientOrderSubmission->approval_status = $status;
                $clientOrderSubmission->save();
                //submissionOrderCompletedEmailNotify($clientOrder->client_id);

            }else{
                $returnMsg = 'Something went wrong, please try again';
            }

            return redirect()->route('admin.client-orders.list')->with(['success' => $returnMsg]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => SOMETHING_WENT_WRONG]);
        }
    }

    public function assignMember(Request $request)
    {
        DB::beginTransaction();
        $successMsg = "Assigned Successfully";
        try {
            $order = ClientOrder::with('client')->find($request->order_id);

            if (!$order) {
                throw new \Exception(__('Order not found'));
            }

            $submission = ClientOrderSubmission::where('client_order_id', $order->order_id)->first();
            $assigneeUser = User::find($request->member_id);

            if ($request->checked_status == 1) {
                $data = ClientOrderAssignee::updateOrCreate(
                    [
                        'order_id' => $request->order_id,
                        'assigned_to' => $request->member_id
                    ],
                    [
                        'assigned_by' => auth()->id(),
                        'is_active' => ACTIVE,
                    ]
                );

                if ($assigneeUser && $assigneeUser->role === USER_ROLE_REVIEWER && $submission) {
                    $token = Str::uuid()->toString();

                    $review = Reviews::firstOrCreate(
                        [
                            'client_order_submission_id' => $submission->id,
                            'client_order_id' => $submission->client_order_id,
                            'reviewer_id' => $assigneeUser->id,
                        ],
                        [
                            'status' => SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW,
                        ]
                    );

                    $metadata = $review->invitation_metadata ?? [];
                    $metadata['token'] = $token;
                    $metadata['generated_at'] = now()->toIso8601String();
                    $metadata['generated_by'] = auth()->id();

                    $review->fill([
                        'invitation_status' => 'pending',
                        'conflict_declared' => false,
                        'conflict_details' => null,
                        'invited_at' => now(),
                        'responded_at' => null,
                        'invitation_metadata' => $metadata,
                    ]);
                    $review->save();

                    $data->fill([
                        'invited_at' => now(),
                        'responded_at' => null,
                        'invitation_status' => 'pending',
                    ]);
                    $data->save();

                    if (getOption('app_mail_status')) {
                        try {
                            $invitationLink = route('reviewer.invitation.show', ['token' => $token]);
                            Mail::to($assigneeUser->email)->send(new ReviewerInvitation($order, $submission, $review, $assigneeUser, $invitationLink));
                        } catch (\Exception $mailException) {
                            Log::error('Reviewer invitation email failed: ' . $mailException->getMessage());
                        }
                    }
                } else {
                    try {
                        orderAssigneMemberEmailNotify($order, $request->member_id);
                    } catch (\Exception $e) {
                        Log::error("Order assignee mail failed: " . $e->getMessage());
                    }
                }
            } else {
                if ($request->member_id == auth()->id() && auth()->user()->role == USER_ROLE_TEAM_MEMBER) {
                    throw new \Exception(__('You cannot unassign yourself'));
                }

                $data = ClientOrderAssignee::where([
                    'order_id' => $request->order_id,
                    'assigned_to' => $request->member_id
                ])->first();

                if ($data) {
                    $data->delete();
                }
                $successMsg = "Unassigned Successfully";
            }

            DB::commit();
            return $this->success($data, $successMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Assign/Unassign Error: " . $e->getMessage());
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }

    }

    public function noteStore(Request $request){

        $request->validate([
            'order_id' => 'required',
            'details' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = ClientOrderNote::find(decrypt($request->id));
                $msg = __("Note Updated Successfully");
            }else {
                $data = new ClientOrderNote();
                $msg = __("Note Created Successfully");
            }
                $data->order_id = decrypt($request->order_id);
                $data->details = $request->details;
                $data->user_id = auth()->id();
                $data->save();

            DB::commit();
            return $this->success([], $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function noteDelete($id){
        try {
            DB::beginTransaction();
            $data = ClientOrderNote::where('id', decrypt($id))->first();
            $data->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function fullview($id){
        $data['pageTitleParent'] = __('Submission');
        $data['pageTitle'] = __('Full View');
        //$data['order'] = ClientOrder::find($id);

        $clientOrder = ClientOrder::where('id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('journal','article_type','supplyment_material_files','authors','authors_roles','funders','__suggested_reviewers')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $clientOrder->order_id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;


        return view("admin.orders.fullview",$data);
    }

}






