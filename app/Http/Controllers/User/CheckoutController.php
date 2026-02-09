<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\CheckoutService;
use App\Http\Services\CouponService;
use App\Http\Services\GatewayService;
use App\Models\Bank;
use App\Models\Service;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    use ResponseTrait;

    public $gatewayService, $couponService, $checkoutService;

    public function __construct()
    {
        $this->gatewayService = new GatewayService;
        $this->couponService = new CouponService;
        $this->checkoutService = new CheckoutService;
    }


    public function gatewayList(Request $request)
    {
        $gateWayService = new GatewayService;
        $data['gateways'] = $gateWayService->getActiveAll(auth()->user()->tenant_id);
        if ($request->type == 'service') {
            $data['itemData'] = Service::find($request->id);
            $data['coupon'] = $this->couponService->getCouponByServiceId($data['itemData']->id);
        }
        $data['banks'] = Bank::where('tenant_id', auth()->user()->tenant_id)->get();
        return view('user.checkout.partials.gateway-list', $data)->render();
    }
    public function currencyList(Request $request)
    {
        $data['currencyList'] = $this->gatewayService->getCurrencyByGatewayId($request->id);
        $data['itemAmount'] = $request->amount;
        return view('user.checkout.partials.currency-list', $data)->render();
    }

    public function checkoutOrderPlace(Request $request)
    {
        Log::debug("checkout order started");
        if (is_null($request->gateway)) {
            return $this->error([], __("Select gateway"));
        }

        if (is_null($request->currency)) {
            return $this->error([], __("Select Currency"));
        }

        return $this->checkoutService->userCheckoutOrder($request);
    }

    // By oubtou
    public function checkoutOrderWithoutpaiement(Request $request)
    {
        //dd($request);
        return $this->checkoutService->usercheckoutOrderWithoutpaiement($request);
    }
    // By oubtou
}
