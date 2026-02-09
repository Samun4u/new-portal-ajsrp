<?php


namespace App\Http\Services;

use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use ResponseTrait;

    public function recentOpenTicketHistory(Request $request)
    {
        $recentOpenHistory = Ticket::leftJoin('users', 'tickets.client_id', '=', 'users.id')
            ->where('users.role', USER_ROLE_CLIENT)
            ->where('tickets.status', TICKET_STATUS_OPEN)
            ->where('tickets.tenant_id', auth()->user()->tenant_id)
            ->select('users.name as userName', 'users.image as userImage', 'tickets.*')
            ->skip(0)
            ->take(5);

        return datatables($recentOpenHistory)
            ->addIndexColumn()
            ->addColumn('userInfo', function ($data) {
                return "<div class='d-flex align-items-center g-8'><div class='flex-shrink-0 w-30 h-30 rounded-circle overflow-hidden'>
                        <img src='" . getFileUrl($data->userImage) . "' alt='' />
                        </div><h4 class='fs-12 fw-500 lh-15 text-para-text text-nowrap'>$data->userName</h4></div>";
            })
            ->addColumn('ticket_id', function ($data) {
                return "<a href='".getRoute($data)."'>$data->ticket_id</a>";
            })
            ->addColumn('priorityStatus', function ($data) {
                if ($data->priority == TICKET_PRIORITY_LOW) {
                    return "<p class=' zPriority-low'>" . __('Low') . "</p>";
                } elseif ($data->priority == TICKET_PRIORITY_MEDIUM) {
                    return "<p class=' zPriority'>" . __('Medium') . "</p>";
                } else {
                    return "<p class=' zPriority-high'>" . __('High') . "</p>";
                }
            })
            ->rawColumns(['userInfo','ticket_id', 'priorityStatus'])
            ->make(true);
    }
    public function ticketSummeryForClient(Request $request)
    {
        $data = Ticket::where(['client_id' => auth()->id()]);

        return datatables($data)
            ->addIndexColumn()

            ->addColumn('status', function ($data) {
                if ($data->status == TICKET_STATUS_OPEN) {
                    return "<p class=' zPriority-low'>" . __('Open') . "</p>";
                } elseif ($data->status == TICKET_STATUS_IN_PROGRESS) {
                    return "<p class=' zPriority'>" . __('Processing') . "</p>";
                } elseif ($data->status == TICKET_STATUS_RESOLVED) {
                    return "<p class=' zPriority'>" . __('Completed') . "</p>";
                } elseif ($data->status == TICKET_STATUS_CLOSED) {
                    return "<p class=' zPriority'>" . __('Closed') . "</p>";
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function recentOpenOrder(Request $request)
    {
        $recentOrderHistory = ClientOrder::leftJoin('users', 'client_orders.client_id', '=', 'users.id')
            ->where('client_orders.working_status', WORKING_STATUS_WORKING)
            ->where('client_orders.tenant_id', auth()->user()->tenant_id)
            ->orderByDesc('client_orders.id')
            ->select([
                'users.name as userName',
                'users.image as userImage',
                'client_orders.*'
            ])
            ->skip(0)
            ->take(5);

        return datatables($recentOrderHistory)
            ->addIndexColumn()
            ->addColumn('userInfo', function ($data) {
                return "<div class='d-flex align-items-center g-8'><div class='flex-shrink-0 w-30 h-30 rounded-circle overflow-hidden'>
                        <img src='" . getFileUrl($data->userImage) . "' alt='' />
                        </div><h4 class='fs-12 fw-500 lh-15 text-para-text text-nowrap'>$data->userName</h4></div>";
            })
            ->addColumn('order_id',function($data){
                if(auth()->user()->role == USER_ROLE_CLIENT){
                    return $data->order_id;
                }else{
                    return "<a href='".route('admin.client-orders.task-board.index', $data->id)."'>$data->order_id</a>";
                }
            })
            ->addColumn('paymentInfo', function ($data) {
                if ($data->payment_status == PAYMENT_STATUS_PENDING) {
                    return "<p class=' zPriority-low'>" . __('Pending') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_PARTIAL) {
                    return "<p class=' zPriority'>" . __('Partial') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_PAID) {
                    return "<p class=' zPriority'>" . __('Paid') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_CANCELLED) {
                    return "<p class=' zPriority'>" . __('Cancelled') . "</p>";
                }
            })
            ->rawColumns(['userInfo','order_id','paymentInfo'])
            ->make(true);
    }
    public function orderSummeryForClient(Request $request)
    {
        $data = ClientOrder::where(['working_status' => WORKING_STATUS_WORKING, 'client_id' => auth()->id()])
        ->orderByDesc('client_orders.id');

        return datatables($data)
            ->addIndexColumn()
            ->addColumn('workingStatus', function ($data) {
                if ($data->payment_status == WORKING_STATUS_WORKING) {
                    return "<p class=' zPriority-low'>" . __('Working') . "</p>";
                } elseif ($data->payment_status == WORKING_STATUS_COMPLETED) {
                    return "<p class=' zPriority'>" . __('Completed') . "</p>";
                } elseif ($data->payment_status == WORKING_STATUS_CANCELED) {
                    return "<p class=' zPriority'>" . __('Cancelled') . "</p>";
                } elseif ($data->payment_status == WORKING_STATUS_PENDING) {
                    return "<p class=' zPriority'>" . __('Pending') . "</p>";
                }
            })
            ->addColumn('paymentStatus', function ($data) {
                if ($data->payment_status == PAYMENT_STATUS_PENDING) {
                    return "<p class=' zPriority-low'>" . __('Pending') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_PARTIAL) {
                    return "<p class=' zPriority'>" . __('Partial') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_PAID) {
                    return "<p class=' zPriority'>" . __('Paid') . "</p>";
                } elseif ($data->payment_status == PAYMENT_STATUS_CANCELLED) {
                    return "<p class=' zPriority'>" . __('Cancelled') . "</p>";
                }
            })
            ->rawColumns(['workingStatus', 'paymentStatus'])
            ->make(true);
    }


    public function revenueOverviewChartData($request, $user_type = null)
    {
        try {
            $year = $request->year != null ? $request->year : Carbon::now()->year;
            $revenueData = ClientInvoice::select(
                DB::raw("DATE_FORMAT(created_at, '%b') month"),
                DB::raw('sum(total) as revenue')
            )
                ->whereYear('created_at', $year)
                ->where(['payment_status' => PAYMENT_STATUS_PAID, 'tenant_id' => auth()->user()->tenant_id,])
                ->groupBy('month')
                ->get()
                ->toArray();

            $year = Carbon::now()->year;
            $monthList = [];

            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::create($year, $month, 1)->format('M');
                $monthList[$month] = $monthName;
            }


            $chatData = [];
            foreach ($monthList as $month) {
                $chatData[$month] = 0;
            }
            foreach ($revenueData as $data) {
                $chatData[$data['month']] = $data['revenue'];
            }
            return $this->success($chatData, 'Data Found');
        } catch (\Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }

    public function clientOverviewChartData($request, $user_type = null)
    {
        try {
            $year = $request->year != null ? $request->year : Carbon::now()->year;
            $revenueData = User::select(
                DB::raw("DATE_FORMAT(created_at, '%b') month"),
                DB::raw('count(id) as total_user')
            )
                ->whereYear('created_at', $year)
                ->where(['role' => USER_ROLE_CLIENT, 'tenant_id' => auth()->user()->tenant_id,])
                ->groupBy('month')
                ->get()
                ->toArray();

            $year = Carbon::now()->year;
            $monthList = [];

            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::create($year, $month, 1)->format('M');
                $monthList[$month] = $monthName;
            }


            $chatData = [];
            foreach ($monthList as $month) {
                $chatData[$month] = 0;
            }
            foreach ($revenueData as $data) {
                $chatData[$data['month']] = $data['total_user'];
            }
            return $this->success($chatData, 'Data Found');
        } catch (\Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }

    public function userOverviewChartData($request, $user_type = null)
    {
        try {
            $year = $request->year != null ? $request->year : Carbon::now()->year;
            $revenueData = User::select(
                DB::raw("DATE_FORMAT(created_at, '%b') month"),
                DB::raw('count(id) as total_user')
            )
                ->whereYear('created_at', $year)
                ->where(['role' => USER_ROLE_ADMIN])
                ->groupBy('month')
                ->get()
                ->toArray();
            $year = Carbon::now()->year;
            $monthList = [];

            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::create($year, $month, 1)->format('M');
                $monthList[$month] = $monthName;
            }

            $chatData = [];
            foreach ($monthList as $month) {
                $chatData[$month] = 0;
            }
            foreach ($revenueData as $data) {
                $chatData[$data['month']] = $data['total_user'];
            }
            return $this->success($chatData, 'Data Found');
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}
