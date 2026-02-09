<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Services\CouponService;
use App\Models\Service;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Coupon');
        $data['activeCoupon'] = 'active';
        $data['activeSetting'] = 'active';
        if ($request->ajax()) {
            return $this->couponService->getAllData();
        }
        return view('admin.setting.coupon.index', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Coupon');
        $data['pageTitle'] = __('Add Coupon');
        $data['activeCoupon'] = 'active';
        $data['activeSetting'] = 'active';
        $data['service'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();
        return view('admin.setting.coupon.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Coupon');
        $data['pageTitle'] = __('Edit Coupon');
        $data['activeCoupon'] = 'active';
        $data['activeSetting'] = 'active';
        $data['coupon'] = $this->couponService->getById($id);
        $data['service'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();
        return view('admin.setting.coupon.edit', $data);
    }

    public function store(CouponRequest $request)
    {
        return $this->couponService->store($request);
    }

    public function delete($id)
    {
        return $this->couponService->deleteById($id);
    }
    public function applyCoupon(Request $request)
    {
        return $this->couponService->applyCoupon($request);
    }
}
