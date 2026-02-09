<?php

namespace App\Http\Services;

use App\Models\Faq;
use App\Models\FeaturesSetting;
use App\Traits\ResponseTrait;
use App\Models\FileManager;
use Exception;
use Illuminate\Support\Facades\DB;

class FaqService
{
    use ResponseTrait;

    public function list(){
        $faq = Faq::all();
        return datatables($faq)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return '<div class="zBadge zBadge-active">'.__("Active").'</div>';
                } else {
                    return '<div class="zBadge zBadge-inactive">'.__("Deactivate").'</div>';
                }
            })
            ->addColumn('action', function ($data) {
                return '<div class="d-flex align-items-center g-10 justify-content-end">
                                <button onclick="getEditModal(\'' . route('super-admin.setting.faq.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="Edit">
                                    <img src="' . asset('assets/images/icon/edit-black.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteItem(\'' . route('super-admin.setting.faq.delete', $data->id) . '\', \'faqDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" title="Delete">
                                    <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                                </button>
                        </div>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function faqStore($request){
        try {
            DB::beginTransaction();

            $id = $request->get('id', 0);
            $faq = Faq::find($id);
            if (is_null($faq)) {
                $faq = new Faq();
            }
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->status = isset($request->status) ? $request->status : ACTIVE;
            $faq->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function faqDelete($id)
    {
        try {
            $data = Faq::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

}
