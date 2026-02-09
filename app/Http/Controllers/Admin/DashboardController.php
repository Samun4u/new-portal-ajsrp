<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTrait;

    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dashboardService->recentOpenTicketHistory($request);
        }
        $data['pageTitle'] = __('Dashboard');
        $data['isDashboard'] = true;
        $data['activeDashboard'] = 'active';
        $data['totalUser'] = 0;
        $data['totalCustomer'] = 0;
        $data['totalClientCount'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->where('status', STATUS_ACTIVE)->count();
        $data['totalTeamMemberCount'] = User::where('role', USER_ROLE_TEAM_MEMBER)->where('tenant_id', auth()->user()->tenant_id)->where('status', STATUS_ACTIVE)->count();
        $data['totalCompletedOrder'] = ClientOrder::where(['working_status' => WORKING_STATUS_COMPLETED, 'tenant_id' => auth()->user()->tenant_id])->count();
        $data['totalOpenOrder'] = ClientOrder::where(['working_status' => WORKING_STATUS_WORKING, 'tenant_id' => auth()->user()->tenant_id])->count();
        $data['totalRevenue'] = ClientInvoice::where(['payment_status' => PAYMENT_STATUS_PAID, 'tenant_id' => auth()->user()->tenant_id])->sum('total');
        $data['yearlyRevenue'] = ClientInvoice::where(['payment_status' => PAYMENT_STATUS_PAID, 'tenant_id' => auth()->user()->tenant_id])
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        return view('admin.dashboard', $data);
    }

    public function revenueOverviewChartData(Request $request)
    {
        return $this->dashboardService->revenueOverviewChartData($request);
    }

    public function clientOverviewChartData(Request $request)
    {
        return $this->dashboardService->clientOverviewChartData($request);
    }

    public function recentOpenOrder(Request $request)
    {
        return $this->dashboardService->recentOpenOrder($request);
    }
}
