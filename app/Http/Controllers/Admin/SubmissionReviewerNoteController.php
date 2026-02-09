<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionReviewerRequest;
use App\Http\Services\SubmissionReviewerNoteService;
use App\Models\ClientOrder;
use App\Models\SubmissionReviewerNotes;
use App\Models\SubmissionReviewerNotesConversation;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionReviewerNoteController extends Controller
{
    use ResponseTrait;

    private $submissionReviewerNotService;

    public function __construct()
    {
        $this->submissionReviewerNotService = new SubmissionReviewerNoteService;
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->submissionReviewerNotService->ticketList($request->status);
        }

        $data['pageTitle'] = __('Ticket');
        $data['activeTicket'] = 'active';
        $data['ticketCount'] = $this->submissionReviewerNotService->ticketCount();
        return view('admin.ticket.list', $data);
    }


    public function addNew($order_id)
    {
        $data['pageTitleParent'] = __('Reviewer Note');
        $data['pageTitle'] = __('Add Reviewer Note');
        $data['activeOrder'] = 'active';
        // $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'created_by' => auth()->id()])->get();
        $data['clientOrder'] = ClientOrder::with('client_order_submission')->where([
            'tenant_id' => auth()->user()->tenant_id,
            'id' => decrypt($order_id)
        ])->first();
        // $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        $data['reviewerList'] = $users = DB::table('client_order_assignees')
            ->join('users', 'client_order_assignees.assigned_to', '=', 'users.id')
            ->where('client_order_assignees.order_id', decrypt($order_id))
            ->where('users.role', USER_ROLE_REVIEWER)
            ->where('client_order_assignees.deleted_at', null)
            ->select('users.*') // 
            ->get();
        return view('admin.submission_reviewer_note.add-new', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Reviewer Note');
        $data['pageTitle'] = __('Edit Reviewer Note');
        $data['activeOrder'] = 'active';
        // $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'created_by' => auth()->id()])->get();
        //$data['clientOrderList'] = ClientOrder::where(['tenant_id' => auth()->user()->tenant_id])->get();
        // $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        $data['ticketDetails'] = $this->submissionReviewerNotService->ticketDetails(decrypt($id));
        $data['clientOrder'] = ClientOrder::where([
            'tenant_id' => auth()->user()->tenant_id,
            'order_id' => $data['ticketDetails']->order_id
        ])->first();
        $data['reviewerList'] = $users = DB::table('client_order_assignees')
            ->join('users', 'client_order_assignees.assigned_to', '=', 'users.id')
            ->where('client_order_assignees.order_id', $data['clientOrder']->id)
            ->where('users.role', USER_ROLE_REVIEWER)
            ->where('client_order_assignees.deleted_at', null)
            ->select('users.*') // 
            ->get();
        // $assigneeList = [];
        // foreach ($data['ticketDetails']->assignee as $key => $assignee) {
        //     $assigneeList[$key] = $assignee->assigned_to;
        // }
        // $data['ticketAssignee'] = $assigneeList;
        return view('admin.submission_reviewer_note.edit', $data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Reviewer Note');
        $data['pageTitle'] = __('Reviewer Note Details');
        $data['activeOrder'] = 'active';
        // $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'created_by' => auth()->id()])->get();
        // $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        $data['ticketDetails'] = $this->submissionReviewerNotService->ticketDetails(decrypt($id));
        $data['clientOrder'] = ClientOrder::with('client')->where([
            'tenant_id' => auth()->user()->tenant_id,
            'order_id' => $data['ticketDetails']->order_id
            ])
        ->first();
        $data['ticketConversations'] = $this->submissionReviewerNotService->ticketConversations(decrypt($id));
        // $assigneeList = [];
        // if($data['ticketDetails'] != null) {
        //     foreach ($data['ticketDetails']->assignee as $key => $assignee) {
        //         $assigneeList[$key] = $assignee->assigned_to;
        //     }
        // }
        // $data['ticketAssignee'] = $assigneeList;

        // $seenUneenData = TicketSeenUnseen::where(['ticket_id'=>decrypt($id), 'created_by'=>auth()->id()])->first();
        // if(is_null($seenUneenData)){
        //     $seenUneenData = new TicketSeenUnseen();
        //     $seenUneenData->ticket_id = decrypt($id);
        //     $seenUneenData->created_by = auth()->id();
        //     $seenUneenData->is_seen = 1;
        //     $seenUneenData->tenant_id = auth()->user()->tenant_id;
        // }else{
        //     $seenUneenData->is_seen = 1;
        // }
        // $seenUneenData->save();

        return view('admin.submission_reviewer_note.details', $data);
    }


    public function store(SubmissionReviewerRequest $request)
    {
        return $this->submissionReviewerNotService->store($request);
    }

    public function conversationsStore(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'conversation_text' => 'required',
            'file' => 'nullable|array',
            'file.*' => 'file|mimes:pdf,docx,png|max:10240', // 10MB per file
        ]);
        return $this->submissionReviewerNotService->conversationsStore($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $submissionReviewerNotService = SubmissionReviewerNotes::where('id', decrypt($id))->first();
            $submissionReviewerNotService->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function conversationsDelete($id)
    {
        try {
            DB::beginTransaction();
            $ticketConversationData = SubmissionReviewerNotesConversation::where('id', decrypt($id))->first();
            $ticketConversationData->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

//     public function search(Request $request)
//     {
//         try {
// //            $searchData = Service::where('service_name', 'LIKE', "%$request->keyword%")->get();
//             $data['serviceList'] = Service::where(['user_id' => auth()->id(), 'status' => ACTIVE])
//                 ->where('service_name', 'LIKE', "%$request->keyword%")
//                 ->orderBy('id', 'DESC')
//                 ->get();
//             $responseData = view('admin.service.search-render', $data)->render();
//             return $this->success($responseData, 'Data Found');
//         } catch (\Exception $e) {
//             return $this->error([], getErrorMessage($e, $e->getMessage()));
//         }
//     }

    // public function assignMember(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         if ($request->checked_status == 1) {
    //             $data = new TicketAssignee();
    //             $data->ticket_id = $request->ticket_id;
    //             $data->assigned_to = $request->member_id;
    //             $data->assigned_by = auth()->id();
    //             $data->is_active = ACTIVE;
    //             $data->save();
    //             assigneMemberEmailNotify($request->ticket_id, $data->assigned_to);
    //         } else {
    //             $data = TicketAssignee::where(['ticket_id' => $request->ticket_id, 'assigned_to' => $request->member_id])->first();
    //             $data->delete();
    //         }
    //         DB::commit();
    //         $data['datatable'] = isset($request->data_table) ? $request->data_table : '';
    //         return $this->success($data, 'Assignee Update');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return $this->error([], getErrorMessage($e, $e->getMessage()));
    //     }
    // }

    public function priorityChange($submission_reviewer_note_id, $priority_id)
    {
        DB::beginTransaction();
        try {
            $data = SubmissionReviewerNotes::find(decrypt($submission_reviewer_note_id));
            $data->priority = $priority_id;
            $data->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'Priority Change successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong!']);
        }
    }

    // public function statusChange(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $data = Ticket::find(decrypt($request->ticket_id));
    //         $data->status = $request->status;
    //         $data->save();

    //         DB::commit();
    //         ticketStatusChangeEmailNotify(decrypt($request->ticket_id));
    //         ticketStatusChangeNotify(decrypt($request->ticket_id));
    //         return $this->success([], 'Status Change successfully');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return $this->error([], getErrorMessage(SOMETHING_WENT_WRONG));
    //     }
    // }

}
