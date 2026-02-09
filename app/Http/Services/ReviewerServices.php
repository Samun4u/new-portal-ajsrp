<?php

namespace App\Http\Services;

use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\FileManager;
use App\Models\User;
use App\Models\UserDetails;
use Exception;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Clue\StreamFilter\fun;


class ReviewerServices
{
    use ResponseTrait;

    public function getClientListData(Request $request)
    {
        $clientInfo = User::where('role', USER_ROLE_REVIEWER)
            ->where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('notes_submitted') && $request->notes_submitted == 'yes') {
            $clientInfo->where(function($q) {
                $q->whereHas('reviewerNotes')
                  ->orWhereHas('reviews', function($sq) {
                      $sq->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
                  });
            });
        }

        $clientInfo->orderBy('created_at', 'DESC');

        return datatables($clientInfo)
            ->addIndexColumn()
            ->addColumn('user_name', function ($clientInfo) {
                return "<a href='" . route('admin.reviewer.details', encrypt($clientInfo->id)) . "' class='d-flex align-items-center g-8'>
                <div class='flex-shrink-0 w-30 h-30 rounded-circle overflow-hidden'>
                    <img src='" . getFileUrl($clientInfo->image) . "' alt='image' />
                </div>
                <h4 class='fs-12 fw-500 lh-15 text-para-text text-nowrap'>" . $clientInfo->name . "</h4>
            </a>";
            })
            ->addColumn('user_email', function ($clientInfo) {
                return "<p class='text-nowrap'>$clientInfo->email</p>";
            })
            ->addColumn('company_name', function ($clientInfo) {
                return "<a href='#' class='fs-12 fw-500 lh-15 text-para-text'>" . ($clientInfo->company_name ?? 'N/A') . "</a>";
            })
            ->addColumn('status', function ($clientInfo) {
                if ($clientInfo->status == STATUS_ACTIVE) {
                    return '<div class="zBadge zBadge-active">'.__("Active").'</div>';
                } else {
                    return '<div class="zBadge zBadge-pending">'.__("Deactivate").'</div>';
                }
            })
            ->addColumn('action', function ($data) {

                $returnAction ="<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>";

                            $returnAction .="<li>
                        <a class='d-flex align-items-center cg-8' href='" . route('admin.reviewer.details', encrypt($data->id)) . "'>
                        <div class='d-flex'>
                       <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A' /><path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'/></svg> </div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Profile') . "</p></a>
                        </li>
                        ";

                        if(auth()->user()->role !== USER_ROLE_PEER_REVIEWER_MANAGER){
                        $returnAction .=
                        "
                        <li><a class='d-flex align-items-center cg-8' href='" . route('admin.reviewer.edit', encrypt($data->id)) . "'>
                        <div class='d-flex'><svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg><p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Edit') . "</p></a></li>
                        <li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='deleteItem(\"" . route('admin.reviewer.delete', encrypt($data->id)) . "\", \"clientListDatatable\")'>
                        <div class='d-flex'><svg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd'clip-rule='evenodd'd='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'fill='#5D697A'/></svg></div>
                        <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Delete') . "</p></button></li>
                        ";
                        }

                        $returnAction .= "</ul></div>";
                        return $returnAction;

            })
            ->rawColumns(['user_name', 'user_email','status', 'company_name', 'action'])
            ->make(true);
    }

    public function getOrderHisatoryData(Request $request)
    {
        $assignOrderIds = ClientOrderAssignee::where('assigned_to',$request->id)->pluck('order_id')->toArray();
        $orderHistory = ClientOrder::Query()
            ->whereIn('id', $assignOrderIds)
            ->orderBy('created_at', 'desc')
            ->with(['client_order_items']);

        return datatables($orderHistory)
            ->addIndexColumn()
            ->addColumn('service_name', function ($orderHistory) {
                $itemIds = '';
                $last_key = count($orderHistory->client_order_items);
                foreach ($orderHistory->client_order_items as $key => $item) {
                    $sep = '';
                    if ($last_key - 1 != $key) {
                        $sep = ', ';
                    }
                    $itemIds .= getServiceById($item->service_id, 'service_name') . $sep;
                }
                return '<p>' . $itemIds . '</p>';
            })
            ->addColumn('total', function ($orderHistory) {
                return showPrice($orderHistory->total);
            })
            ->addColumn('transaction_amount', function ($orderHistory) {
                return showPrice($orderHistory->transaction_amount);
            })
            ->addColumn('order_id', function ($orderHistory) {
                return $orderHistory->order_id;
            })
            ->addColumn('working_status', function ($orderHistory) {
                if ($orderHistory->working_status == WORKING_STATUS_WORKING) {
                    return '<div class="zBadge zBadge-open">' . __('Working') . '</div>';
                } elseif ($orderHistory->working_status == WORKING_STATUS_COMPLETED) {
                    return '<div class="zBadge zBadge-complete">' . __('Completed') . '</div>';
                } elseif ($orderHistory->working_status == WORKING_STATUS_CANCELED) {
                    return '<div class="zBadge zBadge-cancel">' . __('Canceled') . '</div>';
                } elseif ($orderHistory->working_status == WORKING_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                }
            })
            ->addColumn('payment_status', function ($orderHistory) {
                if ($orderHistory->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>';
                } elseif ($orderHistory->payment_status == PAYMENT_STATUS_PAID) {
                    return '<div class="zBadge zBadge-complete">' . __('Paid') . '</div>';
                } elseif ($orderHistory->payment_status == PAYMENT_STATUS_PARTIAL) {
                    return '<div class="zBadge zBadge-complete">' . __('Partial') . '</div>';
                } elseif ($orderHistory->payment_status == PAYMENT_STATUS_CANCELLED) {
                    return '<div class="zBadge zBadge-cancel">' . __('Cancelled') . '</div>';
                }
            })
            ->rawColumns(['status', 'service_name', 'working_status', 'payment_status'])
            ->make(true);
    }

    public function getInvoiceHistoryData(Request $request)
    {
        $invoiceHisatory = ClientInvoice::query()
            ->leftJoin('gateways', 'client_invoices.gateway_id', '=', 'gateways.id')
            ->select('gateways.title as gateway_name', 'client_invoices.*')
            ->where('client_id', '=', $request->id)
            ->orderBy('created_at', 'desc');

        return datatables($invoiceHisatory)
            ->addIndexColumn()
            ->addColumn('status', function ($invoiceHistory) {
                if ($invoiceHistory->payment_status == PAYMENT_STATUS_PAID) {
                    return '<div class="zBadge zBadge-complete">' . __('Paid') . '</div>';
                } elseif ($invoiceHistory->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>';
                } else {
                    return '<div class="zBadge zBadge-cancel">' . __('Cancelled') . '</div>';
                }
            })
            ->addColumn('total', function ($order) {
                return showPrice($order->total);
            })
            ->rawColumns(['status', 'total'])
            ->make(true);
    }

    public function store(Request $request)
    {

         // ✅ Step 1: Validation rules
        $rules = [
            'client_name'           => 'required|string|max:191',
            'client_email'          => 'required|email|max:191|unique:users,email' . ($request->id ? ',' . $request->id : ''),
            'status'                => 'required|in:0,1',
            'client_password'       => $request->id ? 'nullable|min:6' : 'required|min:6',
            'client_phone_number'   => 'nullable|string|max:20',
            'client_company_name'   => 'nullable|string|max:191',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // if(isAddonInstalled('ENCYSAAS')) {
            //     $userPackage = userCurrentPackage(Auth::user()->tenant_id);

            //     dd($userPackage);

            //     $totalClient = User::where(['role' => USER_ROLE_CLIENT, 'tenant_id' => auth()->user()->tenant_id])->count();

            //     if (is_null($userPackage) || ($userPackage->number_of_client <= $totalClient)) {
            //         return $this->error([], __("Your client limit is over!"));
            //     }
            // }

            // dd($request->all());

            if ($request->id) {
                $data = User::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
                $type = 'update';
            } else {
                $data = new User();
                $msg = getMessage(CREATED_SUCCESSFULLY);
                $type = 'create';
            }
            $data->name = $request->client_name;
            $data->email = $request->client_email;
            $data->status = $request->status;
            $data->role = USER_ROLE_REVIEWER;
            $data->email_verification_status = STATUS_ACTIVE;
            $data->created_by = auth()->id();
            if ($request->client_password) {
                $data->password = Hash::make($request->client_password);
            }
            $data->mobile = $request->client_phone_number;
            $data->company_name = $request->client_company_name;
            if ($request->image) {
                if ($request->id) {
                    $file = FileManager::where('id', $data->image)->first();
                    if ($file) {
                        $file->removeFile();
                        $uploaded = $file->upload('Service', $request->image, '', $file->id);
                    } else {
                        $file = new FileManager();
                        $uploaded = $file->upload('Service', $request->image);
                    }
                } else {
                    $file = new FileManager();
                    $uploaded = $file->upload('Service', $request->image);
                }
                $data->image = $uploaded->id;
            }
            $data->tenant_id = auth()->user()->tenant_id;

            $data->save();
            DB::commit();

            //sent welcome mail
            if ($type == 'create') {
                reviewerWelcomeEmailNotify($data);
            }
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $clientData = User::where('id', decrypt($request->id))->first();
            $clientData->delete();
            $userDetailsData = UserDetails::where('user_id', $request->id)->first();
            if ($userDetailsData) {
                $userDetailsData->delete();
            }
            $file = FileManager::find($clientData->image);
            if ($file) {
                $file->removeFile();
                $file->delete();
            }
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
}
