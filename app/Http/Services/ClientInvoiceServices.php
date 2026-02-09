<?php

namespace App\Http\Services;

use App\Models\ClientInvoice;
use App\Models\ClientOrderItem;
use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use App\Models\PrimaryCertificate;
use App\Models\Service;
use App\Models\UserActivityLog;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ClientInvoiceServices
{
    use ResponseTrait;

    public function getClientInvoiceListData($request, $id = null)
    {

        $clientInfo = ClientInvoice::with(['clientOrderItems', 'gateway'])->with('order',function($q){
            $q->with('client_order_submission');
        })->orderByDesc('client_invoices.id');
        if ($id) {
            $clientInfo->where('client_id', $id);
            $status = 'all';
        } else {
            $status = 0;
            if ($request->status == PAYMENT_STATUS_PAID) {
                $status = PAYMENT_STATUS_PAID;
            } else if ($request->status == PAYMENT_STATUS_PENDING) {
                $status = PAYMENT_STATUS_PENDING;
            } else if ($request->status == PAYMENT_STATUS_CANCELLED) {
                $status = PAYMENT_STATUS_CANCELLED;
            } else if ($request->status == 'all') {
                $status = 'all';
            }
            if ($status != 'all') {
                $clientInfo->where('payment_status', $status);
            }
            if (auth()->user()->role == USER_ROLE_CLIENT) {
                $clientInfo->where('client_id', auth()->id());
            }elseif (auth()->user()->role == USER_ROLE_FINANCIAL_MANAGER) {
                $clientInfo->where('tenant_id', auth()->user()->tenant_id)
                ->whereHas('order.client_order_submission', function ($q) {
                    $q->where('approval_status', SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED);
                });
            }
            else {
                $clientInfo->where('tenant_id', auth()->user()->tenant_id);
            }
        }
        return datatables($clientInfo)
            ->addIndexColumn()
//            ->addColumn('service_name', function ($clientInfo) {
//                $serviceName = '';
//                $last_key = count($clientInfo->clientOrderItems);
//                foreach ($clientInfo->clientOrderItems as $key => $item) {
//                    $sep = '';
//                    if ($last_key - 1 != $key) {
//                        $sep = ', ';
//                    }
//                    $serviceName .= getServiceById($item->service_id, 'service_name') . $sep;
//                }
//                return $serviceName !== '' ? $serviceName : 'N/A';
//            })
            ->addColumn('invoice_id', function ($clientInfo) {
                if(auth()->user()->role == USER_ROLE_CLIENT){
                    $invoiceId = "<button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='getEditModal(\"" . route('user.client-invoice.details', $clientInfo->id) . "\", \"#invoicePreviewModal\")'>" . $clientInfo->invoice_id . "</button>";
                }else{
                    $invoiceId = $clientInfo->invoice_id;
                }
                return $invoiceId;
            })
            ->addColumn('order_name', function ($clientInfo) {
                return $clientInfo->order?->order_id;
            })
            ->addColumn('gateway_name', function ($clientInfo) {
                return $clientInfo->gateway?->title;
            })
            ->addColumn('order_id', function ($clientInfo) {
                return auth()->user()->role == USER_ROLE_CLIENT ? $clientInfo->order?->client_order_submission?->article_title : $clientInfo->order?->order_id;
            })
            ->addColumn('client_name', function ($clientInfo) {
                return $clientInfo->client?->name;
            })
            ->addColumn('total_price', function ($clientInfo) {
                return showPrice($clientInfo->total);
            })
            ->addColumn('due_date', function ($clientInfo) {
                return date('j F Y', strtotime($clientInfo->due_date));
            })
            ->addColumn('proofreading_status', function ($clientInfo) {
                $metadataStatus = $clientInfo->order?->client_order_submission?->metadata_status;
                if ($metadataStatus == 'approved') {
                    return '<p class="zBadge zBadge-paid">' . __("Completed") . '</p>';
                } else {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('status', function ($clientInfo) {
                if ($clientInfo->payment_status == PAYMENT_STATUS_PAID) {
                    return '<p class="zBadge zBadge-paid">' . __("Paid") . '</p>';
                } elseif ($clientInfo->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                } else {
                    return '<p class="zBadge zBadge-cancel">' . __("Cancelled") . '</p>';
                }
            })
            ->addColumn('action', function ($clientInfo) use ($status) {
                if(auth()->user()->role !== USER_ROLE_CLIENT){

                    $return = "<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>";
                }else{
                    $return = '<ul style="display: flex; flex-direction: column; align-items: flex-end; list-style: none; padding: 0;">';
                }

                if ((($clientInfo->payment_status == PAYMENT_STATUS_PENDING || $clientInfo->payment_status == PAYMENT_STATUS_CANCELLED) || ($status == PAYMENT_STATUS_PENDING || $status == PAYMENT_STATUS_CANCELLED)) && (auth()->user()->role == USER_ROLE_ADMIN || auth()->user()->role == USER_ROLE_TEAM_MEMBER || auth()->user()->role == USER_ROLE_FINANCIAL_MANAGER)) {
                    if ($clientInfo->create_type == CREATE_TYPE_ACTIVE) {
                        $return .= "<li><a class='d-flex align-items-center cg-8' href='" . route('admin.client-invoice.edit', $clientInfo->id) . "'>
                            <div class='d-flex'><svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg><p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Edit') . "</p></a></li>";
                    }
// else {
                        $return .= "<li><button class='border-0 bg-transparent d-flex align-items-center cg-8' onclick='getEditModal(\"" . route("admin.client-invoice.payment-edit", $clientInfo->id) . "\"" . ", \"#showPaymentModal\")'>
                                <div class='d-flex'><svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg></div><p class='fs-14 fw-500 lh-17 text-nowrap text-para-text'>" . __('Status Change') . "</p></button></li>";
//                    }
                }
                //create invoice button ============
                if (auth()->user()->role == USER_ROLE_ADMIN && auth()->user()->role == USER_ROLE_TEAM_MEMBER && auth()->user()->role !== USER_ROLE_FINANCIAL_MANAGER) {

                    $return .= "<li>
                        <button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent create-invoice-btn' onclick='window.location.href=\"" . route('admin.client-invoice.add-new') . "\"'>
                            <div class='d-flex'>
                                <svg width='15' height='15' viewBox='0 0 15 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                    <path d='M7.5 1V14M1 7.5H14' stroke='#5D697A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                </svg>
                            </div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Create Invoice') . "</p>
                        </button>
                    </li>";
                }

                //view invoice button ============
                if (auth()->user()->role == USER_ROLE_ADMIN || auth()->user()->role == USER_ROLE_TEAM_MEMBER ||  auth()->user()->role == USER_ROLE_FINANCIAL_MANAGER) {
                    $return .= "<li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='getEditModal(\"" . route('admin.client-invoice.details', $clientInfo->id) . "\", \"#invoicePreviewModal\")'>";
                    $return .= "<div class='d-flex'>
                            <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                                </path>
                            </svg>
                            </div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p></button></li>";
                } else {
                    if(auth()->user()->role !== USER_ROLE_FINANCIAL_MANAGER){

                        if ($clientInfo->payment_status == PAYMENT_STATUS_PENDING) {
                            $return .= "
                                                <li>
                                                    <a href='#' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent payNowBtn' data-data='" . json_encode($clientInfo) . "'>
                                                        <div class='d-flex'>
                                                            <img src='" . asset('assets/images/icon/dollar-circle.svg') . "' alt='' />
                                                        </div>
                                                        <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Pay Now') . "</p>
                                                    </a>
                                                </li>";
                        }
                    }
                    // $return .= "<li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='getEditModal(\"" . route('user.client-invoice.details', $clientInfo->id) . "\", \"#invoicePreviewModal\")'>";
                }
                // $return .= "<div class='d-flex'>
                //             <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                //                 <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                //                 </path>
                //             </svg>
                //             </div>
                //             <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p></button></li>";

                if ((auth()->user()->role == USER_ROLE_ADMIN || auth()->user()->role == USER_ROLE_TEAM_MEMBER || auth()->user()->role == USER_ROLE_SUPER_ADMIN) && $clientInfo->payment_status != PAYMENT_STATUS_PAID) {
                    $return .= "<li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='deleteItem(\"" . route('admin.client-invoice.delete', $clientInfo->id) . "\", \"invoiceTable-$status\")'>
                            <div class='d-flex'><svg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd'clip-rule='evenodd'd='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'fill='#5D697A'/></svg></div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Delete') . "</p></button></li>";
                }

                if ($clientInfo->gateway_id != null && $clientInfo->gateway->slug == 'bank') {
                    $return .= "<li>
                                <a href='" . getFileUrl($clientInfo->bank_deposit_slip_id) . "' class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent'>
                                    <div class='d-flex'>
                                        <img src='" . asset('assets/images/icon/download-2.svg') . "' alt='' />
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Download') . "</p>
                                </a>
                            </li>";
                }

                if(auth()->user()->role !== USER_ROLE_CLIENT ){

                    $return .= "</ul>
                            </div>";
                }else{
                    $return .= "</ul>";
                }

                return $return;
            })
            ->rawColumns(['invoice_id','status', 'action', 'service_name', 'total_price', 'due_date', 'order_name', 'gateway_name', 'order_id', 'proofreading_status'])
            ->make(true);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            $allDiscount = 0;
            $subTotal = 0;

            if ($id != '') {
                $invoice = ClientInvoice::find($id);
                $clientOrder = ClientOrder::where('id', $invoice->order_id)->first();

                if (!$request->order_id) {
                    $chekDataExist = ClientOrderItem::where('order_id', $invoice->order_id)->get();
                    if (count($chekDataExist) > 0) {
                        foreach ($chekDataExist as $item) {
                            $item->delete();
                        }
                    }
                }
            } else {
                $invoice = new ClientInvoice();
                $clientOrder = new ClientOrder();
            }

            if ($request->order_id && $request->order_id != null) {
                // save ClientInvoice data
                $clientOrder = ClientOrder::findOrFail($request->order_id);
                $invoice->client_id = $request->client_id;
                $invoice->due_date = $request->due_date;
                $invoice->tenant_id = auth()->user()->tenant_id;
                $invoice->total = $request->payable_amount;
                $invoice->order_id = $request->order_id;
                $invoice->payable_amount = $request->payable_amount;
                $invoice->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
                $clientOrder->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
                $clientOrder->working_status = WORKING_STATUS_PENDING;
                if ($request->payment_status == PAYMENT_STATUS_PAID) {
                    $clientOrder->increment('transaction_amount', $request->payable_amount);
                    if ($clientOrder->transaction_amount >= $clientOrder->total) {
                        $clientOrder->payment_status = PAYMENT_STATUS_PAID;
                        $clientOrder->working_status = WORKING_STATUS_WORKING;
                    }
                }
                $clientOrder->save();

                $invoice->transaction_id = $request->payment_status ?? uniqid();
                $invoice->create_type = CREATE_TYPE_ACTIVE;
                $invoice->save();
                $invoice->invoice_id = 'INV-' . sprintf('%06d', $invoice->id);
                $invoice->save();
            } else {
                // Get Total Amount
                foreach ($request->types as $key => $types) {
                    $totalSum = $request->price[$key] * $request->quantity[$key];
                    $subTotal = $subTotal + $totalSum - $request->discount[$key];
                    $allDiscount = $allDiscount + $request->discount[$key];
                }

                // save client order data
                $clientOrder->client_id = $request->client_id;
                $clientOrder->tenant_id = auth()->user()->tenant_id;
                $clientOrder->total = $subTotal;
                $clientOrder->discount = $allDiscount;
                $clientOrder->order_create_type = CREATE_TYPE_DEACTIVATE;
                $clientOrder->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
                $clientOrder->working_status = WORKING_STATUS_PENDING;
                if ($clientOrder->payment_status == PAYMENT_STATUS_PAID) {
                    $clientOrder->working_status = WORKING_STATUS_WORKING;
                }
                $clientOrder->save();
                $clientOrder->order_id = 'ODR-' . sprintf('%06d', $clientOrder->id);
                $clientOrder->save();

                // save Client Invoice data
                $invoice->client_id = $request->client_id;
                $invoice->due_date = $request->due_date;
                $invoice->tenant_id = auth()->user()->tenant_id;
                $invoice->total = $subTotal;
                $invoice->order_id = $clientOrder->id;
                $invoice->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
                $invoice->create_type = CREATE_TYPE_ACTIVE;
                $invoice->save();
                $invoice->invoice_id = 'INV-' . sprintf('%06d', $invoice->id);
                $invoice->save();

                // save client order item data
                foreach ($request->types as $key => $types) {
                    $totalQuantity = $request->price[$key] * $request->quantity[$key];
                    $subTotalAmount = $totalQuantity - $request->discount[$key];

                    $dataObj = new ClientOrderItem();
                    $dataObj->service_id = $request->service_id[$key];
                    $dataObj->price = $request->price[$key];
                    $dataObj->discount = $request->discount[$key];
                    $dataObj->quantity = $request->quantity[$key];
                    $dataObj->order_id = $clientOrder->id;
                    $dataObj->total = $subTotalAmount;
                    $dataObj->save();
                };
            }

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getService()
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

    public function getOrder($id)
    {
        try {
            $data['order'] = ClientOrder::where('client_id', $id)->where('payment_status', PAYMENT_STATUS_PENDING)->get();
            if (is_null($data['order'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
            return $data['order'];
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function invoiceCount()
    {
        return ClientInvoice::where(['tenant_id' => auth()->user()->tenant_id])->count();
    }

    public function delete($id)
    {
        try {
            $invoice = ClientInvoice::findOrFail($id);
            $invoice->delete();

            $order = ClientOrder::findOrFail($invoice->order_id);
            $order->delete();

            $chekDataExist = ClientOrderItem::where('order_id', $invoice->order_id)->get();
            if (count($chekDataExist) > 0) {
                foreach ($chekDataExist as $item) {
                    $item->delete();
                }
            }
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function paymentStatusChange($request, $id)
    {
        DB::beginTransaction();
        try {
            $invoice = ClientInvoice::find($id);
            $clientOrder = ClientOrder::where('id', $invoice->order_id)->first();

            $clientOrder->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
            $invoice->payment_status = $request->payment_status ?? PAYMENT_STATUS_PENDING;
            if ($request->payment_status == PAYMENT_STATUS_PAID) {
                $clientOrder->increment('transaction_amount', $request->payable_amount);
                if ($clientOrder->transaction_amount >= $clientOrder->total) {
                    $clientOrder->payment_status = PAYMENT_STATUS_PAID;

                    //check client submission order and update approval status start =================================
                        $clientOrderSubmission = ClientOrderSubmission::with('journal')->where('client_order_id', $clientOrder->order_id)->first();
                        if (!is_null($clientOrderSubmission)) {
                            $clientOrderSubmission->approval_status = SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW;
                            $clientOrderSubmission->save();

                            //create or get and send primary certificate start =============================================================
                            $primaryCertificate = PrimaryCertificate::where('client_order_id', $clientOrderSubmission->client_order_id)->first();
                            if(!$primaryCertificate){
                                $authors = $clientOrderSubmission->authors;

                                // $affiliations = [];
                                // foreach($authors as $author){
                                //     $affiliations = array_merge($affiliations, json_decode($author->affiliation, true));
                                // }
                                // $affiliations = array_unique($affiliations);
                                // $affiliations = implode(', ', $affiliations);

                                $affiliationGroups = [];

                                foreach ($authors as $author) {
                                    $decoded = json_decode($author->affiliation, true);

                                    if (is_array($decoded)) {
                                        if (isset($decoded[0]) && is_string($decoded[0])) {
                                            $affiliationGroups[] = implode(', ', $decoded);
                                        }
                                        elseif (isset($decoded[0]) && is_array($decoded[0])) {
                                            foreach ($decoded as $item) {
                                                $affiliationGroups[] = implode(', ', array_values($item));
                                            }
                                        }
                                    }
                                }


                                if (count($affiliationGroups) > 1) {
                                    $affiliations = implode(' | ', $affiliationGroups);
                                } else {
                                    $affiliations = implode('', $affiliationGroups);
                                }


                                $authors = $authors->map(function ($author) {
                                    return $author->first_name . ' ' . $author->last_name;
                                });

                                $authors = implode(', ', $authors->toArray());

                                //journal title
                                $journal = $clientOrderSubmission->journal;
                                $journal = $journal->title;

                                $paper_title = $clientOrderSubmission->article_title;

                                $primaryCertificate = new PrimaryCertificate();
                                $primaryCertificate->client_order_id = $clientOrderSubmission->client_order_id;
                                $primaryCertificate->author_names = $authors;
                                $primaryCertificate->author_affiliations = $affiliations;
                                $primaryCertificate->journal_name = $journal;
                                $primaryCertificate->paper_title = $paper_title;
                                $primaryCertificate->save();
                            }

                            primaryCertificateEmailNotifyForCustomer($primaryCertificate->client_order_id);
                            PrimaryCertificateNotifyForCustomer($primaryCertificate->client_order_id);


                            //create or get and send primary certificate end =============================================================
                        }
                    //check client submission order and update approval status end =================================
                }
                $clientOrder->working_status = WORKING_STATUS_WORKING;
            }
            $clientOrder->save();

            $invoice->transaction_id = $request->payment_status ?? uniqid();
            $invoice->create_type = CREATE_TYPE_ACTIVE;
            $invoice->save();
            $invoice->invoice_id = 'INV-' . sprintf('%06d', $invoice->id);
            $invoice->save();

            //send email notification to client
            if ($request->payment_status == PAYMENT_STATUS_PAID) {
                invoiceManuallyPaidMailNotify($invoice->id);
            }

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function userActivity(Request $request, $user_id)
    {
        if ($request->ajax()) {

            if (!$user_id) {
                return redirect()->back()->with(['dismiss' => __('User Not found.')]);
            }
            $item = UserActivityLog::where(['user_id' => $user_id])->orderBy('id', 'DESC')->get();

            return datatables($item)
                ->addColumn('created_at', function ($item) {
                    return date('Y-m-d H:i:s', strtotime($item->created_at));
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
    }
}
