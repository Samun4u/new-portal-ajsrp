<?php

namespace App\Http\Services;

use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\FileManager;
use App\Models\FinalCertificate;
use App\Models\User;
use App\Models\UserDetails;
use Exception;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Clue\StreamFilter\fun;


class FinalCertificateServices
{
    use ResponseTrait;

    public function getFinalCertificateListData(Request $request)
    {
        $finalCertificateInfo = FinalCertificate::query()->where('status', STATUS_ACTIVE);

        if(auth()->user()->role == USER_ROLE_PUBLISHER){
            $finalCertificateInfo->whereHas('client_order.client_order_submission', function ($query) {
                $query->whereIn('approval_status', [
                    SUBMISSION_ORDER_STATUS_ACCEPTED,
                    SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
                ]);
            });
        }
        
        $finalCertificateInfo->orderByDesc('id');

        return datatables($finalCertificateInfo)
            ->addIndexColumn()
            ->addColumn('sl_no', function ($finalCertificateInfo) {
                return $finalCertificateInfo->id;
            })
            ->addColumn('order_id', function ($finalCertificateInfo) {
                return $finalCertificateInfo->client_order_id;
            })
            ->addColumn('author_names', function ($finalCertificateInfo) {
                return $finalCertificateInfo->author_names;
            })
            ->addColumn('author_affiliations', function ($finalCertificateInfo) {
                return $finalCertificateInfo->author_affiliations;
            })
            ->addColumn('paper_title', function ($finalCertificateInfo) {
                return $finalCertificateInfo->paper_title;
            })
            ->addColumn('journal_name', function ($finalCertificateInfo) {
                return $finalCertificateInfo->journal_name;
            })
            ->addColumn('volume', function ($finalCertificateInfo) {
                return $finalCertificateInfo->volume;
            })
            ->addColumn('issue', function ($finalCertificateInfo) {
                return $finalCertificateInfo->issue;
            })
            ->addColumn('date', function ($finalCertificateInfo) {
                return $finalCertificateInfo->date;
            })
            ->addColumn('action', function ($data) {

                $actionHtml ="<div class='dropdown dropdown-one'>
                            <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                        <li>
                        <a class='d-flex align-items-center cg-8' target='_blank' href='" . route('admin.certificate.final.print', encrypt($data->id)) . "'>
                        <div class='d-flex'>
                       <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A' /><path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'/></svg> </div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('View') . "</p></a>
                        </li>";

                        if(
                            auth()->user()->role == USER_ROLE_PUBLISHER &&
                            $data->client_order->client_order_submission->approval_status !== SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
                        ){
                            $actionHtml.="<li><a class='d-flex align-items-center cg-8' href='" . route('admin.certificate.final.edit', encrypt($data->id)) . "'>
                            <div class='d-flex'><svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg><p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Edit') . "</p></a></li>";
                        }
                        

                        $actionHtml.="<li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='sendItem(\"" . route('admin.certificate.final.send', encrypt($data->id)) . "\", \"clientListDatatable\")'>
                        <div class='d-flex'><svg width='18' height='18' viewBox='0 0 24 24' fill='#5D697A' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z'/>
                            </svg>
                        </div>
                        <p class='fs-14 fw-500 lh-17 text-para-text'>" . __('Send') . "</p></button></li>";

                        $actionHtml.="</ul></div>";

                        return $actionHtml;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function storeFinalCertificate(Request $request)
    {
        DB::beginTransaction();
        try {
            

            

            if ($request->id) {
                $data = FinalCertificate::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);

                $existFinalCertificate  = FinalCertificate::where('client_order_id', $request->client_order_id)->whereNot('id',$data->id)->first();
                if($existFinalCertificate){
                    return $this->error([], getMessage(ALREADY_EXIST));
                }


            } else {
                
                $data = new FinalCertificate();
                $msg = getMessage(CREATED_SUCCESSFULLY);

                $existFinalCertificate  = FinalCertificate::where('client_order_id', $request->client_order_id)->first();
                if($existFinalCertificate){
                    
                    return $this->error([], getMessage(ALREADY_EXIST));
                }
            }
            $data->client_order_id = $request->client_order_id;
            $data->author_names = $request->author_names;
            $data->author_affiliations = $request->author_affiliations;
            $data->paper_title = $request->paper_title;
            $data->journal_name = $request->journal_name;
            $data->volume = $request->volume;
            $data->issue = $request->issue;
            $data->date =  $request->date;
        
            $data->save();
            DB::commit();
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }


}
