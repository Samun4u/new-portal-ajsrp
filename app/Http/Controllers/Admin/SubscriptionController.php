<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
// use App\Http\Services\Saas\SubscriptionService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    public $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService;
    }

    public function orders(Request $request)
    {
        if ($request->ajax()) {
            return $this->orderService->allOrder($request);
        } else {
            $data['pageTitle'] = __('All Orders');
            $data['activeSubscriptionIndex'] = 'active';
            return view('sadmin.order.orders', $data);
        }
    }

    public function ordersStatus(Request $request)
    {
        if ($request->ajax()) {
            return $this->orderService->allOrder($request);
        }
    }

    public function orderGetInfo(Request $request)
    {
        $data = $this->orderService->getOrder($request->id);
        return $this->success($data);
    }

    public function orderPaymentStatusChange(Request $request)
    {
        return $this->orderService->paymentStatusUpdate($request);
    }

    public function index()
    {
        $data['pageTitle'] = __('My Subscription');
        // $data['userPlan'] = $this->subscriptionService->getCurrentPlan();
        $orderService = new OrderService;
        // $data['invoices'] = $orderService->getAllUserPackageByUser()->getData()->data;
        // $data['orders'] = $orderService->getPendingOrderByUser();
        $count=0;
        foreach ($data['orders'] as $item){
            if($item->payment_status == 0){
                $count++;
            }
        }
        $data['pendingData'] = $count;

        return view('saas.admin.my_subscriptions.index', $data);
    }
}
