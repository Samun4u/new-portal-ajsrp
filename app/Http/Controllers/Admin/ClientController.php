<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Http\Services\ClientInvoiceServices;
use App\Http\Services\ClientServices;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\User;


class ClientController extends Controller
{
    use ResponseTrait;

    public $clientServices;

    public function __construct()
    {
        $this->clientServices = new ClientServices();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->clientServices->getClientListData($request);
        } else {
            $data['pageTitle'] = __('Client list');
            $data['activeClientIndex'] = 'active';
            $data['clientList'] = User::where('role', USER_ROLE_CLIENT)
                ->where('status', STATUS_ACTIVE)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->get();
            return view('admin.client.list', $data);

        }
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Client');
        $data['pageTitle'] = __('Add Client');
        $data['activeClientIndex'] = 'active';
        return view('admin.client.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Client');
        $data['pageTitle'] = __('Edit Client');
        $data['activeClientIndex'] = 'active';
        $data['clientDetails'] = User::with(['userDetail'])->findOrFail(decrypt($id));

        return view('admin.client.edit', $data);
    }

    public function store(ClientRequest $request)
    {
        return $this->clientServices->store($request);
    }

    public function delete(Request $request)
    {
        return $this->clientServices->delete($request);
    }

    public function details(Request $request, $id)
    {
        if ($request->ajax()) {
            return $this->clientServices->getOrderHisatoryData($request);
        }
        $data['pageTitleParent'] = __('Client');
        $data['pageTitle'] = __('Client Details');
        $data['activeClientIndex'] = 'active';
        $data['clientDetails'] = User::with(['userDetail'])->findOrFail(decrypt($id));

        return view('admin.client.details', $data);
    }

    public function clientInvoiceHistory(Request $request, $id = null ){
        $clientInvoiceService = new ClientInvoiceServices();

        if($request->ajax()){
            return $clientInvoiceService->getClientInvoiceListData($request, $id);
        }
    }
    public function clientActivityHistory(Request $request, $userId){

        $clientInvoiceService = new ClientInvoiceServices();

        if($request->ajax()){
            return $clientInvoiceService->userActivity($request, $userId);
        }
    }

    public function updateStatus(Request $request,$id)
    {
        try {
            $status = $request->input('status');
            $client = User::with(['userDetail'])->findOrFail(decrypt($id));
            $client->status = $status;
            $client->save();
            return $this->success([], __("Status updated successfully"));
        }catch (\Exception $e){
            return $this->error([], $e->getMessage());
        }
    }
}






