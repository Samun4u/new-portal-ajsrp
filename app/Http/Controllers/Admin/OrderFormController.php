<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderFormRequest;
use App\Http\Services\OrderFormManageService;
use App\Http\Services\ServiceManagerService;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderFormController extends Controller
{
    public $serviceManagerService, $orderFormService;

    public function __construct()
    {
        $this->serviceManagerService = new ServiceManagerService;
        $this->orderFormService = new OrderFormManageService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Order Forms');
        $data['activeOrderForm'] = 'active';
        if ($request->ajax()) {
            return $this->orderFormService->getAllData($request);
        }
        return view('admin.order-form.index', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Order Forms');
        $data['pageTitle'] = __('Add Order Forms');
        $data['activeOrderForm'] = 'active';
        $data['services'] = Service::where(['tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])->get();
        return view('admin.order-form.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Order Forms');
        $data['pageTitle'] = __('Edit Order Forms');
        $data['activeOrderForm'] = 'active';
        $data['services'] = $this->serviceManagerService->getAll();
        $data['orderForm'] = $this->orderFormService->getById(decrypt($id));
        $data['orderFormServices'] = $this->orderFormService->getServiceById($data['orderForm']->id);
        return view('admin.order-form.edit', $data);
    }

    public function store(OrderFormRequest $request)
    {
        return $this->orderFormService->store($request);
    }

    public function delete($id)
    {
        return $this->orderFormService->deleteById($id);
    }
}
