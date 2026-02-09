<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use AWS\CRT\Log;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuotationItem;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\QuotationService;
use App\Http\Requests\Admin\QuotationRequest;

class QuotationController extends Controller
{
    use ResponseTrait;

    public $quotationService;

    public function __construct()
    {
        $this->quotationService = new QuotationService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->quotationService->getQuatationListData($request);
        }
        $data['quatationCount'] = $this->quotationService->quatationCount();
        $data['pageTitle'] = __('Quotation list');
        $data['activeQuatationList'] = 'active';
        return view('admin.quotation.list', $data);
    }

    public function add()
    {
        $data['pageTitle'] = __('Add New Quotation');
        $data['activeQuatationList'] = 'active';
        $data['service'] = $this->quotationService->getService();
        return view('admin.quotation.add', $data);
    }

    public function getService()
    {
        $data = $this->quotationService->getService();
        return $this->success($data);
    }

    public function store(QuotationRequest $request)
    {
        return $this->quotationService->store($request);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Edit Quotation');
        $data['activeQuatationList'] = 'active';
        $data['quotation'] = Quotation::find(decrypt($id));
        $data['quotation_items'] = QuotationItem::where('quotation_id', decrypt($id))->get();
        $data['service'] = Service::where(['tenant_id' => auth()->user()->tenant_id])->get();
        return view('admin.quotation.edit', $data);
    }

    public function details($id)
    {
        $data['quotation'] = Quotation::find(decrypt($id));
        $data['quotation_items'] = QuotationItem::where('quotation_id', decrypt($id))->get();
        return view('admin.quotation.quotation-details', $data);
    }

    public function quotationPrint($id)
    {
        $data['title'] = __('Quotation Print');
        $data['quotation'] = Quotation::find(decrypt($id));
        $data['quotation_items'] = QuotationItem::where('quotation_id', decrypt($id))->get();
        return view('admin.quotation.print-quotation', $data);
    }

    public function delete($id)
    {
        return $this->quotationService->delete($id);
    }
    public function quotationSend($id)
    {
        $data = Quotation::find(decrypt($id));
        if($data && $data !=null){
            $res = sendQuotationToClientEmail($data->id);
            if ($res == true){
                return redirect()->back()->with(['success' => __('Mail sent successfully')]);
            }else{
                return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);
            }
        }else{
            return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);
        }
    }
    public function quotationPreview($id, $status = null)
    {
        $data = Quotation::find(decrypt($id));
        if($status == QUOTATION_STATUS_VIEWED){
            $data->status = QUOTATION_STATUS_VIEWED;
            $data->save();
        }

        $checkoutData = ['user_id' => $data->user_id, 'tenant_id' => $data->tenant_id, 'id' => $data->id, 'type' => CHECKOUT_TYPE_QUOTATION];
        $url = route('checkout', encrypt($checkoutData));

        $data['checkout_url'] = $url;
        $data['quotation'] = $data;
        $data['quotation_items'] = QuotationItem::where('quotation_id', decrypt($id))->get();
        return view('frontend.quotation-preview', $data);
    }

    public function quotationCancel($id, $view_status = null)
    {
        try {
            $data = Quotation::find(decrypt($id));
            if($view_status == QUOTATION_STATUS_CANCELED){
                $data->status = QUOTATION_STATUS_CANCELED;
                $data->save();
            }

            $data['quotation'] = $data;
            $data['quotation_items'] = QuotationItem::where('quotation_id', decrypt($id))->get();
            return redirect()->back()->with(['success' => __('Cancel successfully')]);

        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);

        }

    }


}

