<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Services\TicketService;
use App\Models\ClientOrder;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketAssignee;
use App\Models\TicketConversation;
use App\Models\TicketSeenUnseen;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    use ResponseTrait;

    private $ticketService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->ticketService->ticketList($request->status);
        }

        $data['pageTitle'] = __('Ticket');
        $data['activeTicket'] = 'active';
        $data['ticketCount'] = $this->ticketService->ticketCount();
        return view('user.ticket.list', $data);
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Add Ticket');
        $data['activeTicket'] = 'active';
        $data['clientOrderList'] = ClientOrder::where(['client_id' => auth()->id()])->get();
        return view('user.ticket.add-new', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Edit Ticket');
        $data['activeTicket'] = 'active';
        $data['clientOrderList'] = ClientOrder::where(['client_id' => auth()->id()])->get();
        $data['ticketDetails'] = $this->ticketService->ticketDetails(decrypt($id));
        $assigneeList = [];
        foreach ($data['ticketDetails']->assignee as $key => $assignee) {
            $assigneeList[$key] = $assignee->assigned_to;
        }
        $data['ticketAssignee'] = $assigneeList;
        return view('user.ticket.edit', $data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Ticket Details');
        $data['activeTicket'] = 'active';
        $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'created_by' => auth()->id()])->get();
        $data['clientOrderList'] = ClientOrder::where(['tenant_id' => auth()->user()->tenant_id])->get();
        $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        $data['ticketDetails'] = $this->ticketService->ticketDetails(decrypt($id));
        $data['ticketConversations'] = $this->ticketService->ticketConversations(decrypt($id));
        $assigneeList = [];
        if($data['ticketDetails'] != null){
            foreach ($data['ticketDetails']->assignee as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['ticketAssignee'] = $assigneeList;

        $seenUneenData = TicketSeenUnseen::where(['ticket_id'=>decrypt($id), 'created_by'=>auth()->id()])->first();
        if(is_null($seenUneenData)){
            $seenUneenData = new TicketSeenUnseen();
            $seenUneenData->ticket_id = decrypt($id);
            $seenUneenData->created_by = auth()->id();
            $seenUneenData->is_seen = 1;
            $seenUneenData->tenant_id = auth()->user()->tenant_id;
        }else{
            $seenUneenData->is_seen = 1;
        }
        $seenUneenData->save();

        return view('user.ticket.details', $data);
    }


    public function store(TicketRequest $request)
    {
        return $this->ticketService->store($request);
    }

    public function conversationsStore(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'conversation_text' => 'required',
        ]);
        return $this->ticketService->conversationsStore($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $ticketData = Ticket::where('id', decrypt($id))->first();
            $ticketData->delete();
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
            $ticketConversationData = TicketConversation::where('id', decrypt($id))->first();
            $ticketConversationData->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function search(Request $request)
    {
        try {
//            $searchData = Service::where('service_name', 'LIKE', "%$request->keyword%")->get();
            $data['serviceList'] = Service::where(['user_id' => auth()->id(), 'status' => ACTIVE])
                ->where('service_name', 'LIKE', "%$request->keyword%")
                ->orderBy('id', 'DESC')
                ->get();
            $responseData = view('admin.service.search-render', $data)->render();
            return $this->success($responseData, 'Data Found');
        } catch (\Exception $e) {
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function assignMember(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->checked_status == 1) {
                $data = new TicketAssignee();
                $data->ticket_id = $request->ticket_id;
                $data->assigned_to = $request->member_id;
                $data->assigned_by = auth()->id();
                $data->is_active = ACTIVE;
                $data->save();
            } else {
                $data = TicketAssignee::where(['ticket_id' => $request->ticket_id, 'assigned_to' => $request->member_id])->first();
                $data->delete();
            }
            DB::commit();
            $data['datatable'] = isset($request->data_table) ? $request->data_table : '';
            return $this->success($data, 'Assignee Update');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function priorityChange($ticket_id, $priority_id)
    {
        DB::beginTransaction();
        try {
            $data = Ticket::find(decrypt($ticket_id));
            $data->priority = $priority_id;
            $data->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'Priority Change successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong!']);
        }
    }

    public function statusChange(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = Ticket::find(decrypt($request->ticket_id));
            $data->status = $request->status;
            $data->save();
            DB::commit();
            ticketStatusChangeEmailNotify(decrypt($request->ticket_id));
            return $this->success([], 'Status Change successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage(SOMETHING_WENT_WRONG));
        }
    }

}
