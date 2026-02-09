<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutOrderPlaceRequest;
use App\Http\Services\CheckoutService;
use App\Http\Services\CouponService;
use App\Http\Services\GatewayService;
use App\Http\Services\OrderFormManageService;
use App\Http\Services\QuotationService;
use App\Http\Services\SettingsService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ResponseTrait;
    public $settingsService, $couponService, $gatewayService, $checkoutService, $orderFormManageService, $quotationService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
        $this->couponService = new CouponService();
        $this->gatewayService = new GatewayService();
        $this->checkoutService = new CheckoutService();
        $this->orderFormManageService = new OrderFormManageService();
        $this->quotationService =  new QuotationService();
    }

    public function checkout($hash)
    {
        try {
            $paramData = decrypt($hash);
            if ($paramData['type'] == CHECKOUT_TYPE_ORDER_FORM) {
                $data['orderForm'] = $this->orderFormManageService->getById($paramData['id'], $paramData['user_id']);
                $data['orderFormServices'] = $this->orderFormManageService->getServiceById($paramData['id']);
            } elseif ($paramData['type'] == CHECKOUT_TYPE_QUOTATION) {
                $data['quotation'] = $this->quotationService->getById($paramData['id'], $paramData['tenant_id']);
                $data['orderFormServices'] = $this->quotationService->getServiceById($paramData['id']);
            } else {
                return back()->with('error', __(SOMETHING_WENT_WRONG));
            }

            $data['pageTitle'] = __('Checkout');
            $data['type'] = $paramData['type'];
            $data['gateways'] = $this->gatewayService->getAll($paramData['tenant_id']);
            $data['banks'] = $this->gatewayService->banks($paramData['tenant_id']);

            return view('frontend.checkout', $data);
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function checkoutOrder(CheckoutOrderPlaceRequest $request)
    {
        return $this->checkoutService->checkoutOrder($request);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data =  $this->checkoutService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }

    public function getCouponInfo(Request $request)
    {
        return $this->couponService->getCouponInfo($request);
    }
}
