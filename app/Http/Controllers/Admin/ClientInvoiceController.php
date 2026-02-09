<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientInvoiceRequest;
use App\Http\Services\ClientInvoiceServices;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderItem;
use App\Models\QuotationItem;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    use ResponseTrait;

    public $clientInvoiceService;

    public function __construct()
    {
        $this->clientInvoiceService = new ClientInvoiceServices();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->clientInvoiceService->getClientInvoiceListData($request);
        }
        $data['pageTitle'] = __('Client Invoice list');
        $data['activeClientInvoiceIndex'] = 'active';
        $data['invoiceCount'] = $this->clientInvoiceService->invoiceCount();
        return view('admin.client-invoice.list', $data);
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Invoice');
        $data['pageTitle'] = __('Add New Invoice');
        $data['activeClientInvoiceIndex'] = 'active';
        $data['service'] = $this->clientInvoiceService->getService();
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        return view('admin.client-invoice.add-invoice', $data);
    }

    public function store(ClientInvoiceRequest $request)
    {
        return $this->clientInvoiceService->store($request);
    }

    public function delete($id)
    {
        return $this->clientInvoiceService->delete($id);
    }

    public function details($id)
    {
        $data['clientInvoice'] = ClientInvoice::with(['client', 'gateway'])->find($id);
        $data['clientInvoiceOrder'] = ClientOrder::where('id', $data['clientInvoice']->order_id)->first();
        if(is_null($data['clientInvoiceOrder']->quotation_id)){
            $data['invoiceItem'] = ClientOrderItem::where('order_id', $data['clientInvoice']->order_id)
                ->leftJoin('services',['client_order_items.service_id' => 'services.id'])
                ->get();
        }else{
            $data['invoiceItem'] = QuotationItem::where('quotation_id', $data['clientInvoiceOrder']->quotation_id)->get();
        }

        return view('admin.client-invoice.invoice-details', $data);
    }

    public function paymentEdit($id)
    {
        $data['clientInvoice'] = ClientInvoice::with('client')->find($id);
        return view('admin.client-invoice.payment_edit', $data);
    }

    public function getService()
    {
        $data = $this->clientInvoiceService->getService();
        return $this->success($data);
    }

    public function getOrder(Request $request)
    {
        $data['orders'] = $this->clientInvoiceService->getOrder($request->id);
        return view('admin.client-invoice.list_for_dropdown', $data);
    }

    public function invoicePrint($id)
    {
        $data['title'] = __('Invoice Print');
        $data['clientInvoice'] = ClientInvoice::with('client','gateway')->find($id);
        $data['clientInvoiceOrder'] = ClientOrder::where('id', $data['clientInvoice']->order_id)->first();
        $data['invoiceItem'] = ClientOrderItem::where('order_id', $data['clientInvoice']->order_id)->with('service')->get();
        return view('admin.client-invoice.print-invoice', $data);
    }

    public function edit($id){
        $data['pageTitleParent'] = __('Invoice');
        $data['pageTitle'] = __('Edit Invoice');
        $data['activeClientInvoiceIndex'] = 'active';

        $data['clientInvoice'] = ClientInvoice::find($id);
        $data['invoiceItem'] = ClientOrderItem::where('order_id', $data['clientInvoice']->order_id)->get();
        $data['service'] = $this->clientInvoiceService->getService();
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        $data['orders'] = $this->clientInvoiceService->getOrder($data['clientInvoice']->client_id);

        return view('admin.client-invoice.edit-invoice', $data);
    }

    public function paymentStatusChange(Request $request, $id)
    {
        return $this->clientInvoiceService->paymentStatusChange($request, $id);
    }

}

