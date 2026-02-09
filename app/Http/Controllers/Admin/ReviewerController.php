<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Http\Requests\Admin\ReviewerRequest;
// use App\Http\Services\ClientInvoiceServices;
use App\Http\Services\ReviewerServices;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Stripe\Review;

class ReviewerController extends Controller
{
    use ResponseTrait;

    public $reviewerServices;

    public function __construct()
    {
        $this->reviewerServices = new ReviewerServices();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->reviewerServices->getClientListData($request);
        } else {
            $data['pageTitle'] = __('Reviewer list');
            $data['activeReviewerIndex'] = 'active';
            $data['reviewerList'] = User::where('role', USER_ROLE_REVIEWER)
                ->where('status', STATUS_ACTIVE)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->get();
            return view('admin.reviewer.list', $data);

        }
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Reviewer');
        $data['pageTitle'] = __('Add Reviewer');
        $data['activeReviewerIndex'] = 'active';
        return view('admin.reviewer.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Reviewer');
        $data['pageTitle'] = __('Edit Reviewer');
        $data['activeReviewerIndex'] = 'active';
        $data['reviewerDetails'] = User::with(['userDetail'])->findOrFail(decrypt($id));

        return view('admin.reviewer.edit', $data);
    }

    public function store(ReviewerRequest $request)
    {
        return $this->reviewerServices->store($request);
    }

    public function delete(Request $request)
    {
        return $this->reviewerServices->delete($request);
    }

    public function details(Request $request, $id)
    {
        if ($request->ajax()) {
            return $this->reviewerServices->getOrderHisatoryData($request);
        }
        $data['pageTitleParent'] = __('Reviewer');
        $data['pageTitle'] = __('Reviewer Details');
        $data['activeReviewerIndex'] = 'active';
        $data['reviewerDetails'] = User::with(['userDetail'])->findOrFail(decrypt($id));

        return view('admin.reviewer.details', $data);
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






