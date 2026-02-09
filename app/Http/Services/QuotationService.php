<?php

namespace App\Http\Services;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Service;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class QuotationService
{
    use ResponseTrait;

    public function getQuatationListData()
    {
        $data = Quotation::where(['tenant_id' => auth()->user()->tenant_id])
            ->with('quotation_items')
            ->orderByDesc('quotations.id');

        return datatables($data)
            ->addIndexColumn()

            ->editColumn('url', function ($data) {
                return "<input class='copyUrl' type='hidden' value='" . route('quotation.preview', encrypt($data->id)) . "'>
                        <a target='_blank' href='" . route('quotation.preview', encrypt($data->id)) . "' class='border-0 bg-transparent text-main-color text-decoration-underline'>" . __('Quotation Url') . "</a> <button class='fa-solid fa-copy h6 ps-2 copyUrlBtn border-0 bg-transparent'></button>";
            })
            ->editColumn('customer', function ($data) {
                return $data->client_name;
            })
            ->editColumn('price', function ($data) {
                return showPrice($data->total);
            })
            ->editColumn('date', function ($data) {
                return date('j F Y', strtotime($data->expire_date));
            })
            ->editColumn('status', function ($data) {
                if ($data->status == QUOTATION_STATUS_PENDING) {
                    return '<p class="zBadge zBadge-pending">' . __("Pending") . '</p>';
                } elseif ($data->status == QUOTATION_STATUS_SENT) {
                    return '<p class="zBadge zBadge-paid">' . __("Sent") . '</p>';
                } elseif ($data->status == QUOTATION_STATUS_VIEWED) {
                    return '<p class="zBadge zBadge-paid">' . __("Viewed") . '</p>';
                } elseif ($data->status == QUOTATION_STATUS_PAID) {
                    return '<p class="zBadge zBadge-paid">' . __("Paid") . '</p>';
                } else {
                    return "<p class='zBadge zBadge-inactive bg-red-10'>" . __('Canceled') . "</p>";
                }
            })
            ->addColumn('action', function ($data) {
                $sendBtn = '';
                if ($data->status == QUOTATION_STATUS_PENDING) {
                    $sendBtn = "<li>
                                <a class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' href='" . route('admin.quotation.send', encrypt($data->id)) . "'>
                                    <div class='d-flex'>
                                        <i class='fa-solid fa-envelope'></i>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Send') . "</p></a>
                                </li>";
                }

                return "<div class='dropdown dropdown-one'>
                            <button
                                class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center'
                                type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='fa-solid fa-ellipsis'></i>
                            </button>
                            <ul class='dropdown-menu dropdownItem-two'>
                                <li>
                                    <a class='d-flex align-items-center cg-8' href='" . route('admin.quotation.edit', encrypt($data->id)) . "'>
                                        <div class='d-flex'>
                                            <svg width='12' height='13' viewBox='0 0 12 13' fill='none'
                                                xmlns='http://www.w3.org/2000/svg'>
                                                <path
                                                    d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z'
                                                    fill='#5D697A' />
                                            </svg>
                                        </div>
                                        <p class='fs-14 fw-500 lh-17 text-para-text'>Edit</p>
                                    </a>
                                </li>
                                <li>
                                <button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='getEditModal(\"" . route('admin.quotation.details', encrypt($data->id)) . "\", \"#quotationPreviewModal\")'>
                                    <div class='d-flex'>
                                        <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A'></path> <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'>
                                            </path>
                                        </svg>
                                    </div>
                                    <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View Details') . "</p></button>
                                </li>
                                <li>
                                    <a class='d-flex align-items-center cg-8 delete' type='button' data-url='" . route('admin.quotation.delete', encrypt($data->id)) . "'>
                                        <div class='d-flex'>
                                            <svg width='14' height='15' viewBox='0 0 14 15' fill='none'
                                                xmlns='http://www.w3.org/2000/svg'>
                                                <path fill-rule='evenodd'clip-rule='evenodd'
                                                    d='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'
                                                    fill='#5D697A' />
                                            </svg>
                                        </div>
                                        <p class='fs-14 fw-500 lh-17 text-para-text'>Delete</p>
                                    </a>
                                </li>
                                " . $sendBtn . "
                            </ul>
                        </div>";
            })
            ->rawColumns(['status', 'action', 'url', 'price', 'date', 'customer'])
            ->make(true);
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

    public function quatationCount()
    {
        return Quotation::where(['tenant_id' => auth()->user()->tenant_id])->count();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            $subTotal    = 0;
            $all_total    = 0;

            if ($id != '') {
                $quotation = Quotation::find($request->id);

                $chekDataExist = QuotationItem::where('quotation_id', $id)->get();
                if (count($chekDataExist) > 0) {
                    foreach ($chekDataExist as $item) {
                        $item->delete();
                    }
                }
            } else {
                $quotation = new Quotation();
            }

            // Get Total Amount
            foreach ($request->service_id as $key => $types) {
                $quantity_amount  = $request->price[$key] * $request->quantity[$key];
                $subTotal = $subTotal + $quantity_amount;
                $all_total = $all_total + $quantity_amount;
            }
            // save quotation data
            $quotation->client_name =  $request->client_name;
            $quotation->email       =  $request->email;
            $quotation->description =  $request->description;
            $quotation->tenant_id   = auth()->user()->tenant_id;
            $quotation->user_id   = auth()->user()->id;
            $quotation->sub_total   =  $subTotal;
            $quotation->total       =  $all_total - $request->discount;
            $quotation->discount    = $request->discount;
            $quotation->address    = $request->address;
            $quotation->expire_date    = $request->expire_date;
            $quotation->save();
            $quotation->quotation_id    = 'Q-' . sprintf('%06d', $quotation->id);
            $quotation->save();

            // save quotation item data
            foreach ($request->service_id as $key => $types) {
                $total        = $request->price[$key] * $request->quantity[$key];
                $dataObj              = new QuotationItem();

                if ($request->service_id[$key] == 'new_service') {
                    $service_name = isset($request->service_name[$key]) ? $request->service_name[$key] : '';
                    $service = new Service();
                    $service->service_name = $service_name . "(Custom Service)";
                    $service->service_description =  'quotation item';
                    $service->price =  $request->price[$key];
                    $service->duration =  $request->duration[$key];
                    $service->image =  0;
                    $service->status =  0;
                    $service->user_id =  Auth::id();
                    $service->tenant_id =  auth()->user()->tenant_id;
                    $service->save();
                    $dataObj->service_id  = $service->id;
                    $dataObj->service_name  = isset($request->service_name[$key]) ? $request->service_name[$key] : '';
                } else {
                    $serviceName = Service::find($request->service_id[$key]);
                    $dataObj->service_name  = $serviceName->service_name ?? null;
                    $dataObj->service_id  = $serviceName->id;
                }

                $dataObj->price       = $request->price[$key];
                $dataObj->quantity    = $request->quantity[$key];
                $dataObj->duration    = $request->duration[$key];
                $dataObj->total       = $total;
                $dataObj->quotation_id       = $quotation->id;
                $dataObj->save();
            };
            DB::commit();

            sendQuotationToClientEmail($quotation->id);

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
            $quotation = Quotation::findOrFail(decrypt($id));
            $quotation->delete();

            $chekDataExist = QuotationItem::where('quotation_id', decrypt($id))->get();
            if (count($chekDataExist) > 0) {
                foreach ($chekDataExist as $item) {
                    $item->delete();
                }
            }
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function getById($id, $tenant_id = null)
    {
        $tenant_id = isset($tenant_id) ? $tenant_id : auth()->user()->tenant_id;
        return Quotation::where(['tenant_id' => $tenant_id])->find($id);
    }

    public function getServiceById($id)
    {
        return QuotationItem::query()->where('quotation_id', $id)->get();
    }
}
