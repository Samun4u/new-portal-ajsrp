<?php

namespace App\Http\Services;

use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderConversation;
use App\Models\ClientOrderConversationSeen;
use App\Models\ClientOrderItem;
use App\Models\ClientOrderSubmission;
use App\Models\FileManager;
use App\Models\Reviews;
use App\Models\Service;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class ClientOrderServices
{
    use ResponseTrait;


    public function getClientOrderListData($request)
    {
        $submissionRoles = [
            USER_ROLE_INITIAL_EVALUATOR,
            USER_ROLE_FINANCIAL_MANAGER,
            USER_ROLE_PEER_REVIEWER_MANAGER,
            USER_ROLE_PUBLISHER,
            USER_ROLE_MARKETER
        ];

        $order = ClientOrder::query()
            ->leftJoin('client_order_conversation_seens', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_conversation_seens.order_id')
                    ->where('client_order_conversation_seens.created_by', '=', auth()->id());
            })
            ->orderBy('client_orders.id', 'DESC')
            ->select([
                'client_orders.*',
                'client_order_conversation_seens.is_seen'
            ])
            ->with(['client_order_items','client_order_submission']);

        if ($request->status != 'all') {
            $order->where('client_orders.working_status', $request->status);
        }

        if ($request->has('submission_status') && $request->submission_status != 'all') {
             $order->join('client_order_submissions', function ($join) use ($request) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->where('client_order_submissions.approval_status', $request->submission_status);
            });
        }

        if ($request->has('reviewer_assigned') && $request->reviewer_assigned != 'all') {
            if ($request->reviewer_assigned == 'yes') {
                $order->whereHas('assignee', function($q) {
                    $q->whereHas('reviewer', function($qu) {
                        $qu->where('role', USER_ROLE_REVIEWER);
                    });
                });
            } else {
                $order->whereDoesntHave('assignee', function($q) {
                    $q->whereHas('reviewer', function($qu) {
                        $qu->where('role', USER_ROLE_REVIEWER);
                    });
                });
            }
        }

        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $order->where('client_orders.payment_status', $request->payment_status);
        }

        if (auth()->user()->role == USER_ROLE_CLIENT) {
            $order->where('client_orders.client_id', auth()->id());
        } elseif(auth()->user()->role == USER_ROLE_REVIEWER){
            $assignOrderIds = ClientOrderAssignee::where('assigned_to', auth()->id())->pluck('order_id')->toArray();
            $order->whereIn('client_orders.id', $assignOrderIds);

        } elseif (auth()->user()->role == USER_ROLE_TEAM_MEMBER) {
            $order->join('client_order_assignees', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_assignees.order_id')
                    ->where('client_order_assignees.assigned_to', '=', auth()->id())
                    ->whereNull('client_order_assignees.deleted_at');
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }elseif(auth()->user()->role == USER_ROLE_INITIAL_EVALUATOR){
            $order->join('client_order_submissions', function ($join) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->where('client_order_submissions.approval_status', SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW);
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }elseif(auth()->user()->role == USER_ROLE_FINANCIAL_MANAGER){
            $order->join('client_order_submissions', function ($join) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->where('client_order_submissions.approval_status', SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED);
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }elseif(auth()->user()->role == USER_ROLE_PEER_REVIEWER_MANAGER){
            $order->join('client_order_submissions', function ($join) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->where('client_order_submissions.approval_status', SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW);
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }elseif(auth()->user()->role == USER_ROLE_PUBLISHER){
            $order->join('client_order_submissions', function ($join) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->whereIn('client_order_submissions.approval_status', [
                        SUBMISSION_ORDER_STATUS_ACCEPTED,
                        SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
                    ]);
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }elseif(auth()->user()->role == USER_ROLE_MARKETER){
            $order->join('client_order_submissions', function ($join) {
                $join->on('client_orders.order_id', '=', 'client_order_submissions.client_order_id')
                    ->where('client_order_submissions.approval_status', SUBMISSION_ORDER_STATUS_PUBLISHED);
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }
        else {
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }


        return datatables($order)
            ->addIndexColumn()
            ->addColumn('article_title', function ($order) {
                return $order->client_order_submission ? $order->client_order_submission->article_title : 'N/A';
            })
            ->addColumn('service_name', function ($order) {
                $serviceName = '';
                $last_key = count($order->client_order_items);
                foreach ($order->client_order_items as $key => $item) {
                    $sep = '';
                    if ($last_key - 1 != $key) {
                        $sep = ', ';
                    }
                    $serviceName .= getServiceById($item?->service_id, 'service_name') . $sep;
                }
                return $serviceName !== '' ? $serviceName : 'N/A';
            })
            ->addColumn('total_price', function ($order) {
                return showPrice($order->total);
            })
            ->addColumn('created_at', function ($order) {
                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('j F Y');
                return "<span style='display:none;'>{$order->created_at}</span><p>{$formattedDate}</p>";
            })
            ->addColumn('client_name', function ($order)  use($submissionRoles) {
                if(auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER || in_array(auth()->user()->role, $submissionRoles)){
                    if ($order->is_seen === 0){
                        $data = '<div class="d-flex align-items-center g-5">
                                    <p class="text-nowrap">' . $order->client?->name . '</p>
                                <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div>';
                    }else{
                        $data = '<div class="d-flex align-items-center g-5">
                                <p class="text-nowrap">' . $order->client?->name . '</p>
                                </div>';
                    }
                }else{
                    if ($order->is_seen === 0){
                        $data = '<a href="'. route('admin.client.details', encrypt($order->client?->id)) .'">
                                <div class="d-flex align-items-center g-5">
                                <p class="text-nowrap">' . $order->client?->name . '</p>
                                <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. route('admin.client.details', encrypt($order->client?->id)) .'">
                                <div class="d-flex align-items-center g-5">
                                <p class="text-nowrap">' . $order->client?->name . '</p>
                                </div></a>';
                    }
                }
                return $data;

            })
            ->addColumn('payment_status', function ($order) {
                if ($order->payment_status == PAYMENT_STATUS_PAID) {
                    return '<p class="zBadge zBadge-paid">' . __("Paid") . '</p>';
                } elseif ($order->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                } elseif ($order->payment_status == PAYMENT_STATUS_CANCELLED) {
                    return '<p class="zBadge zBadge-cancel">' . __("Cancelled") . '</p>';
                } elseif ($order->payment_status == PAYMENT_STATUS_PARTIAL) {
                    return '<p class="zBadge zBadge-pending">' . __("Partial") . '</p>';
                }
            })
            ->addColumn('order_status', function ($order) {
                if($order->client_order_submission){
                    if($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Under Primary Review") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Under Peer Review") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Accepted for Publication") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_PUBLISHED){
                        return '<p class="zBadge bg-success text-white fw-normal">' . __("Published") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_INITIAL_REJECTED){
                         $statusText = ucwords(str_replace('_', ' ', SUBMISSION_ORDER_STATUS_INITIAL_REJECTED));
                        return '<p class="zBadge bg-danger text-white fw-normal">' . __($statusText) . '</p>';
                    }else{
                        return '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', $order->client_order_submission->approval_status)) . '</p>';
                    }

                }else{
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('working_status', function ($order) {
                if ($order->working_status == WORKING_STATUS_COMPLETED) {
                    return '<p class="zBadge zBadge-paid">' . __("Completed") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_WORKING) {
                    return '<p class="zBadge zBadge-open">' . __("Working") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_CANCELED) {
                    return '<p class="zBadge zBadge-cancel">' . __("Cancelled") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('order_id', function ($order) {
                // if ($order->is_seen === 0){
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //               <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                //               <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                //             </div>';
                // }else{
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //             </div>';
                // }
                if(auth()->user()->role == USER_ROLE_CLIENT){
                    $orderViewRoute = route('user.orders.dashboard', $order->order_id);

                    if ($order->is_seen === 0){
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }
                } elseif(auth()->user()->role == USER_ROLE_REVIEWER){
                    $orderViewRoute = route('user.orders.task-board.index', $order->id);
                    // $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $order->order_id)->first();
                    // if($clientOrderSubmission){
                    //     $orderViewRoute =  route('user.orders.fullview', $order->id);
                    // }

                    if ($order->is_seen === 0){
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }
                }else{

                    if ($order->is_seen === 0){
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }

                }
                return $data;
            })
            ->addColumn('action', function ($order) {
                if (auth()->user()->role == USER_ROLE_CLIENT) {
                    $submission = $order->client_order_submission;
                    $dashboardRoute = route('user.orders.dashboard', $order->order_id);
                    $revisionRoute = route('user.orders.revision.form', $order->order_id);
                    $feedbackRoute = route('user.orders.reviews.summary', $order->order_id);
                    $taskboardRoute = route('user.orders.task-board.index', $order->id);

                    return "<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button>
                            <ul class='dropdown-menu dropdownItem-two'>
                                <li><a href='" . $dashboardRoute . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path>
                                            <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'></path>
                                        </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Dashboard') . "</p></a>
                                </li>
                                <li><a href='" . $revisionRoute . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <i class='fa-regular fa-pen-to-square text-secondary'></i>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Submit Revision') . "</p></a>
                                </li>
                                <li><a href='" . $feedbackRoute . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <i class='fa-regular fa-comments text-secondary'></i>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Reviewer Feedback') . "</p></a>
                                </li>
                                <li><a href='" . $taskboardRoute . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path>
                                            <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'></path>
                                        </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Task Board') . "</p></a>
                                </li>
                            </ul>
                        </div>";
                } elseif (auth()->user()->role == USER_ROLE_REVIEWER) {
                    return "<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                                <li><a href='" . route('user.orders.task-board.index', $order->id) . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                <div class='d-flex'>
                                <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                    <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                                    </path>
                                </svg>
                                </div>
                                <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p></a></li>
                            </ul>
                        </div>";
                } else {
                    $return = "<div class='dropdown dropdown-one'>
                        <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                            <li>
                                <a class='d-flex align-items-center cg-8' href='" . route('admin.client-orders.task-board.index', $order->id) . "'>
                                    <div class='d-flex'>
                                        <svg width='17' height='17' viewBox='0 0 17 17' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
                                            <path
                                                d='M5.21875 6.75C5.0447 6.75 4.87778 6.81914 4.75471 6.94221C4.63164 7.06528 4.5625 7.2322 4.5625 7.40625C4.5625 7.5803 4.63164 7.74722 4.75471 7.87029C4.87778 7.99336 5.0447 8.0625 5.21875 8.0625C5.3928 8.0625 5.55972 7.99336 5.68279 7.87029C5.80586 7.74722 5.875 7.5803 5.875 7.40625C5.875 7.2322 5.80586 7.06528 5.68279 6.94221C5.55972 6.81914 5.3928 6.75 5.21875 6.75ZM4.5625 12.2188C4.5625 12.0447 4.63164 11.8778 4.75471 11.7547C4.87778 11.6316 5.0447 11.5625 5.21875 11.5625C5.3928 11.5625 5.55972 11.6316 5.68279 11.7547C5.80586 11.8778 5.875 12.0447 5.875 12.2188C5.875 12.3928 5.80586 12.5597 5.68279 12.6828C5.55972 12.8059 5.3928 12.875 5.21875 12.875C5.0447 12.875 4.87778 12.8059 4.75471 12.6828C4.63164 12.5597 4.5625 12.3928 4.5625 12.2188ZM0.625 3.46875C0.625 2.71454 0.924608 1.99122 1.45792 1.45792C1.99122 0.924608 2.71454 0.625 3.46875 0.625H13.5312C14.2855 0.625 15.0088 0.924608 15.5421 1.45792C16.0754 1.99122 16.375 2.71454 16.375 3.46875V13.5312C16.375 14.2855 16.0754 15.0088 15.5421 15.5421C15.0088 16.0754 14.2855 16.375 13.5312 16.375H3.46875C2.71454 16.375 1.99122 16.0754 1.45792 15.5421C0.924608 15.0088 0.625 14.2855 0.625 13.5312V3.46875ZM3.25 7.40625C3.25 7.66479 3.30092 7.9208 3.39986 8.15966C3.4988 8.39852 3.64382 8.61555 3.82663 8.79837C4.00945 8.98118 4.22648 9.1262 4.46534 9.22514C4.7042 9.32408 4.96021 9.375 5.21875 9.375C5.47729 9.375 5.7333 9.32408 5.97216 9.22514C6.21102 9.1262 6.42805 8.98118 6.61087 8.79837C6.79368 8.61555 6.9387 8.39852 7.03764 8.15966C7.13658 7.9208 7.1875 7.66479 7.1875 7.40625C7.1875 6.88411 6.98008 6.38335 6.61087 6.01413C6.24165 5.64492 5.74089 5.4375 5.21875 5.4375C4.69661 5.4375 4.19585 5.64492 3.82663 6.01413C3.45742 6.38335 3.25 6.88411 3.25 7.40625ZM5.21875 10.25C4.69661 10.25 4.19585 10.4574 3.82663 10.8266C3.45742 11.1958 3.25 11.6966 3.25 12.2188C3.25 12.7409 3.45742 13.2417 3.82663 13.6109C4.19585 13.9801 4.69661 14.1875 5.21875 14.1875C5.74089 14.1875 6.24165 13.9801 6.61087 13.6109C6.98008 13.2417 7.1875 12.7409 7.1875 12.2188C7.1875 11.6966 6.98008 11.1958 6.61087 10.8266C6.24165 10.4574 5.74089 10.25 5.21875 10.25ZM8.5 7.40625C8.5 7.7685 8.794 8.0625 9.15625 8.0625H13.0938C13.2678 8.0625 13.4347 7.99336 13.5578 7.87029C13.6809 7.74722 13.75 7.5803 13.75 7.40625C13.75 7.2322 13.6809 7.06528 13.5578 6.94221C13.4347 6.81914 13.2678 6.75 13.0938 6.75H9.15625C8.9822 6.75 8.81528 6.81914 8.69221 6.94221C8.56914 7.06528 8.5 7.2322 8.5 7.40625ZM9.15625 11.5625C8.9822 11.5625 8.81528 11.6316 8.69221 11.7547C8.56914 11.8778 8.5 12.0447 8.5 12.2188C8.5 12.3928 8.56914 12.5597 8.69221 12.6828C8.81528 12.8059 8.9822 12.875 9.15625 12.875H13.0938C13.2678 12.875 13.4347 12.8059 13.5578 12.6828C13.6809 12.5597 13.75 12.3928 13.75 12.2188C13.75 12.0447 13.6809 11.8778 13.5578 11.7547C13.4347 11.6316 13.2678 11.5625 13.0938 11.5625H9.15625ZM3.25 3.46875C3.25 3.831 3.544 4.125 3.90625 4.125H13.0938C13.2678 4.125 13.4347 4.05586 13.5578 3.93279C13.6809 3.80972 13.75 3.6428 13.75 3.46875C13.75 3.2947 13.6809 3.12778 13.5578 3.00471C13.4347 2.88164 13.2678 2.8125 13.0938 2.8125H3.90625C3.7322 2.8125 3.56528 2.88164 3.44221 3.00471C3.31914 3.12778 3.25 3.2947 3.25 3.46875Z'
                                                fill='#5D697A' />
                                        </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Board') . "</p>
                                </a>
                            </li>";


                    if ($order->order_create_type == CREATE_TYPE_ACTIVE) {
                        $return .= "<li><a class='d-flex align-items-center cg-8' href='" . route('admin.client-orders.edit', $order->id) . "'>
                            <div class='d-flex'><svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg><p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Edit') . "</p></a></li>";
                    }

                    if ($order->order_create_type == CREATE_TYPE_ACTIVE) {
                        $return .= "<li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='deleteItem(\"" . route('admin.client-orders.delete', $order->id) . "\", \"allOrderTable\")'>
                            <div class='d-flex'><svg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd'clip-rule='evenodd'd='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'fill='#5D697A'/></svg></div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Delete') . "</p></button></li>";
                    }
                    $return .= "</ul>
                </div>";
                return $return;
            }
        })
        ->filterColumn('article_title', function ($query, $keyword) {
            $query->whereHas('client_order_submission', function ($q) use ($keyword) {
                $q->where('article_title', 'like', '%' . $keyword . '%');
            });
        })
        ->rawColumns(['working_status', 'payment_status', 'order_status', 'action', 'service_name', 'client_name', 'total_price', 'created_at', 'order_id', 'article_title'])
        ->make(true);

    }

    public function getReviewerAssignedOrderListData($request)
    {
        $order = ClientOrder::query()
            ->leftJoin('client_order_conversation_seens', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_conversation_seens.order_id')
                    ->where('client_order_conversation_seens.created_by', '=', auth()->id());
            })
            ->orderBy('client_orders.id', 'DESC')
            ->select([
                'client_orders.*',
                'client_order_conversation_seens.is_seen'
            ])
            ->with(['client_order_items','client_order_submission']);

        if ($request->status != 'all') {
            $order->where('client_orders.working_status', $request->status);
        }

        if (auth()->user()->role == USER_ROLE_CLIENT) {
            $order->where('client_orders.client_id', auth()->id());
        } elseif(auth()->user()->role == USER_ROLE_REVIEWER){
            $assignOrderIds = ClientOrderAssignee::where('assigned_to', auth()->id())->pluck('order_id')->toArray();
            $order->whereIn('client_orders.id', $assignOrderIds);

        } elseif (auth()->user()->role == USER_ROLE_TEAM_MEMBER) {
            $order->join('client_order_assignees', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_assignees.order_id')
                    ->where('client_order_assignees.assigned_to', '=', auth()->id())
                    ->whereNull('client_order_assignees.deleted_at');
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        } else {
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }


        return datatables($order)
            ->addIndexColumn()
            ->addColumn('created_at', function ($order) {
                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('j F Y');
                return "<span style='display:none;'>{$order->created_at}</span><p>{$formattedDate}</p>";
            })
            ->addColumn('paper_title', function ($order) {
                if($order->client_order_submission){
                    return $order->client_order_submission->article_title;
                }else{
                    return '-';
                }
            })
            ->addColumn('status', function ($order) {
                // $defaultStatus = '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW)) . '</p>';
                // if($order->client_order_submission){
                //     $review = Reviews::where('client_order_submission_id', $order->client_order_submission->id)->where('reviewer_id',auth()->id())->first();
                //     if($review){
                //         if($review->status == SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS){
                //             return '<p class="zBadge zBadge-paid" style="color:white ! important">' . ucwords(str_replace('_', ' ', $review->status)) . '</p>';
                //         }elseif($review->status == SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED){
                //             return '<p class="zBadge zBadge-open">' .ucwords(str_replace('_', ' ', $review->status)) . '</p>';
                //         }else{
                //             return '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', $review->status)) . '</p>';
                //         }
                //     }else{
                //         return $defaultStatus;
                //     }

                // }else{
                //     return $defaultStatus;
                // }

                 if ($order->working_status == WORKING_STATUS_COMPLETED) {
                    return '<p class="zBadge zBadge-paid">' . __("Completed") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_WORKING) {
                    return '<p class="zBadge zBadge-open">' . __("Working") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_CANCELED) {
                    return '<p class="zBadge zBadge-cancel">' . __("Cancelled") . '</p>';
                } elseif ($order->working_status == WORKING_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('order_id', function ($order) {
                // if ($order->is_seen === 0){
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //               <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                //               <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                //             </div>';
                // }else{
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //             </div>';
                // }
                if(auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER){
                    $orderViewRoute = route('user.orders.task-board.index', $order->id);
                    // $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $order->order_id)->first();
                    // if($clientOrderSubmission){
                    //     $orderViewRoute =  route('user.orders.fullview', $order->id);
                    // }

                    if ($order->is_seen === 0){
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }
                }else{

                    if ($order->is_seen === 0){
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }

                }
                return $data;
            })
            ->addColumn('action', function ($order) {
                if (auth()->user()->role == USER_ROLE_REVIEWER) {
                    return "<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                                <li><a href='" . route('user.orders.task-board.index', $order->id) . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                <div class='d-flex'>
                                <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                    <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                                    </path>
                                </svg>
                                </div>
                                <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p></a></li>
                            </ul>
                        </div>";
                }
        })
        ->rawColumns(['status', 'action', 'paper_title', 'created_at', 'order_id'])
        ->make(true);

    }

    public function getReviewerSubmissionOrderListData($request)
    {
        $order = ClientOrder::query()
            ->leftJoin('client_order_conversation_seens', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_conversation_seens.order_id')
                    ->where('client_order_conversation_seens.created_by', '=', auth()->id());
            })
            ->orderBy('client_orders.id', 'DESC')
            ->select([
                'client_orders.*',
                'client_order_conversation_seens.is_seen'
            ])
            ->with(['client_order_items','client_order_submission']);

        if ($request->status != 'all') {
            $order->where('client_orders.working_status', $request->status);
        }

        if (auth()->user()->role == USER_ROLE_CLIENT) {
            $order->where('client_orders.client_id', auth()->id());
        } elseif(auth()->user()->role == USER_ROLE_REVIEWER){
            // $assignOrderIds = Reviews::where('reviewer_id', auth()->id())->where('status', 'completed')->pluck('client_order_id')->toArray();
            // $order->whereIn('client_orders.order_id', $assignOrderIds);
            $order->join('client_order_assignees', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_assignees.order_id')
                    ->where('client_order_assignees.assigned_to', '=', auth()->id())
                    ->whereNull('client_order_assignees.deleted_at');
            });
            $order->where('client_orders.working_status', WORKING_STATUS_COMPLETED);

        } elseif (auth()->user()->role == USER_ROLE_TEAM_MEMBER) {
            $order->join('client_order_assignees', function ($join) {
                $join->on('client_orders.id', '=', 'client_order_assignees.order_id')
                    ->where('client_order_assignees.assigned_to', '=', auth()->id())
                    ->whereNull('client_order_assignees.deleted_at');
            });
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        } else {
            $order->where('client_orders.tenant_id', auth()->user()->tenant_id);
        }


        return datatables($order)
            ->addIndexColumn()
            ->addColumn('created_at', function ($order) {
                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('j F Y');
                return "<span style='display:none;'>{$order->created_at}</span><p>{$formattedDate}</p>";
            })
            ->addColumn('paper_title', function ($order) {
                if($order->client_order_submission){
                    return $order->client_order_submission->article_title;
                }else{
                    return '-';
                }
            })
            ->addColumn('status', function ($order) {
                // $defaultStatus = '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_UNDER_REVIEW)) . '</p>';
                // if($order->client_order_submission){
                //     $review = Reviews::where('client_order_submission_id', $order->client_order_submission->id)->where('reviewer_id',auth()->id())->first();
                //     if($review){
                //         if($review->admin_status == SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_ACCEPTED){
                //             return '<p class="zBadge zBadge-open">' . ucwords(str_replace('_', ' ', $review->admin_status)) . '</p>';
                //         }elseif($review->admin_status == SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_REJECTED){
                //             return '<p class="zBadge zBadge-cancel">' .ucwords(str_replace('_', ' ', $review->admin_status)) . '</p>';
                //         }elseif($review->admin_status == SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_PUBLISHED){
                //             return '<p class="zBadge zBadge-active" style="color:white ! important">' .ucwords(str_replace('_', ' ', $review->admin_status)) . '</p>';
                //         }else{
                //             return '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', $review->admin_status)) . '</p>';
                //         }
                //     }else{
                //         return $defaultStatus;
                //     }

                // }else{
                //     return $defaultStatus;
                // }

                if($order->client_order_submission){
                    if($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Under Primary Review") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Under Peer Review") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION){
                        return '<p class="zBadge bg-info text-white fw-normal">' . __("Accepted for Publication") . '</p>';
                    }elseif($order->client_order_submission->approval_status == SUBMISSION_ORDER_STATUS_PUBLISHED){
                        return '<p class="zBadge bg-success text-white fw-normal">' . __("Published") . '</p>';
                    }else{
                        return '<p class="zBadge zBadge-pending">' . ucwords(str_replace('_', ' ', $order->client_order_submission->approval_status)) . '</p>';
                    }

                }else{
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('order_id', function ($order) {
                // if ($order->is_seen === 0){
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //               <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                //               <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                //             </div>';
                // }else{
                //     $data = '<div class="d-flex align-items-center g-5">
                //               <p class="text-nowrap">' . $order->order_id . '</p>
                //             </div>';
                // }
                if(auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER){
                    $orderViewRoute = route('user.orders.fullview', $order->id);
                    // $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $order->order_id)->first();
                    // if($clientOrderSubmission){
                    //     $orderViewRoute =  route('user.orders.fullview', $order->id);
                    // }

                    if ($order->is_seen === 0){
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. $orderViewRoute .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }
                }else{

                    if ($order->is_seen === 0){
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                   <div class="w-16 h-16 rounded-circle bg-green flex-shrink-0 d-flex justify-content-center align-items-center">
                                   <img src="' . asset('assets/images/icon/bell-white.svg') . '" class="max-w-8" alt=""></div>
                                </div></a>';
                    }else{
                        $data = '<a href="'. route('admin.client-orders.task-board.index', $order->id) .'"><div class="d-flex align-items-center g-5">
                                   <p class="text-nowrap">' . $order->order_id . '</p>
                                </div></a>';
                    }

                }
                return $data;
            })
            ->addColumn('action', function ($order) {

                $showEditButton = false;
                if($order->client_order_submission){
                    // $review = Reviews::where('client_order_submission_id', $order->client_order_submission->id)->where('reviewer_id',auth()->id())->first();
                    // if($review){
                    //     if($review->admin_status == SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_UNDER_REVIEW || $review->admin_status == null){
                    //         $showEditButton = true;
                    //     }
                    // }
                    if(!in_array($order->client_order_submission->approval_status,[SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,SUBMISSION_ORDER_STATUS_PUBLISHED])){
                        $showEditButton = true;
                    }
                }

                if (auth()->user()->role == USER_ROLE_REVIEWER) {
                    $actionHtml = "<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                            <li>
                                <a href='" . route('user.orders.fullview', $order->id) . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                                            </path>
                                        </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p>
                                </a>
                            </li>";

                            if($showEditButton){
                                $actionHtml .= "<li>
                                 <a class='d-flex align-items-center cg-8' href='" .route('user.submission.select-a-journal', ['by' => 'by-subject', 'action' => 'update', 'id' => $order->order_id]) . "'>
                                    <div class='d-flex'>
                                       <svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                          <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' />
                                       </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __("Edit") . "</p>
                                 </a>
                              </li>";
                            }

                            $actionHtml .= "</ul>
                        </div>";

                            return $actionHtml;
                }
        })
        ->rawColumns(['status', 'action', 'paper_title', 'created_at', 'order_id'])
        ->make(true);

    }

    public function getInvoice()
    {
        try {
            $data['service'] = Service::where(['tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])->get();
            if (is_null($data['service'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
            return $data['service'];
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            $totalAmount = 0;
            $allDiscount = 0;
            $subTotal = 0;

            if ($id != '') {
                $clientOrder = ClientOrder::find($id);
                $chekDataExist = ClientOrderItem::where('order_id', $id)->get();

                if (count($chekDataExist) > 0) {
                    foreach ($chekDataExist as $item) {
                        $item->delete();
                    }
                }

            } else {
                $clientOrder = new ClientOrder();
            }

            // Get Total Amount
            foreach ($request->types as $key => $types) {
                $totalSum = $request->price[$key] * $request->quantity[$key];
                $subTotal = $subTotal + $totalSum - $request->discount[$key];
                $totalAmount = $totalAmount + $totalSum;
                $allDiscount = $allDiscount + $request->discount[$key];
            }

             // save client order data
             $clientOrder->client_id       =  $request->client_id;
             $clientOrder->tenant_id	     = auth()->user()->tenant_id;
             $clientOrder->total          =  $subTotal;
             $clientOrder->discount       =  $allDiscount;
             $clientOrder->order_create_type =  CREATE_TYPE_ACTIVE;
             $clientOrder->working_status =  WORKING_STATUS_PENDING;
             $clientOrder->save();
             $clientOrder->order_id = 'MS-' . sprintf('%06d', $clientOrder->id);
             $clientOrder->save();

            // save client order item data
            foreach ($request->types as $key => $types) {
                $totalQuantity = $request->price[$key] * $request->quantity[$key];
                $subTotalAmount = $totalQuantity - $request->discount[$key];

                $dataObj = new ClientOrderItem();
                $dataObj->service_id = $request->service_id[$key];
                $dataObj->price = $request->price[$key] ?? 0;
                $dataObj->discount = $request->discount[$key] ?? 0;
                $dataObj->quantity = $request->quantity[$key] ?? 1;
                $dataObj->order_id = $clientOrder->id;
                $dataObj->total = $subTotalAmount;
                $dataObj->save();
            };

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function delete($id)
    {
        try {
            $chekDataExist = ClientOrderItem::where('order_id', $id)->get();
            if (count($chekDataExist) > 0) {
                foreach ($chekDataExist as $item) {
                    $item->delete();
                }
            }
            $order = ClientOrder::findOrFail($id);
            $order->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function conversationStore($request)
    {
        DB::beginTransaction();
        try {
            $dataObj = new ClientOrderConversation();
            $dataObj->order_id = decrypt($request->order_id);
            $dataObj->conversation_text = $request->conversation_text;
            $dataObj->type = $request->type;
            $dataObj->user_id = auth()->id();

            /*File Manager Call upload*/
            if ($request->file) {
                $fileId = [];
                foreach ($request->file as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('ticket-conversation-documents', $singlefile);
                    array_push($fileId, $uploaded->id);
                }
                $dataObj->attachment = json_encode($fileId);
            }
            /*File Manager Call upload*/

            $dataObj->save();
            DB::commit();

            $renderData['conversationClientTypeData'] = ClientOrderConversation::where(['order_id' => decrypt($request->order_id), 'type' => CONVERSATION_TYPE_CLIENT])->get();
            $renderData['conversationTeamTypeData'] = ClientOrderConversation::where(['order_id' => decrypt($request->order_id), 'type' => CONVERSATION_TYPE_TEAM])->get();
            $renderData['type'] = $request->type;

            if (auth()->user()->role == USER_ROLE_CLIENT) {
                $data['conversationClientTypeData'] = view('user.orders.partials.conversation_list_render', $renderData)->render();
            } else {
                $data['conversationClientTypeData'] = view('user.orders.partials.conversation_list_render', $renderData)->render();
                $data['conversationTeamTypeData'] = view('admin.orders.partials.conversation_list_render', $renderData)->render();
            }
            $data['type'] = $request->type;

            ClientOrderConversationSeen::where('order_id', decrypt($request->order_id))
                ->where('created_by', '!=', auth()->id())
                ->update(['is_seen' => 0]);

            ClientOrder::where(['id' => decrypt($request->order_id), 'tenant_id' => auth()->user()->tenant_id])
                ->update([
                    'last_reply_id' => $dataObj->id,
                    'last_reply_by' => auth()->id(),
                    'last_reply_time' => now(),

                ]);

            return $this->success($data, __(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}
