<?php

namespace App\Http\Services;

use App\Traits\ResponseTrait;
use App\Models\FileManager;
use App\Models\ServiceSetting;
use Exception;
use Illuminate\Support\Facades\DB;

class ServiceSettingService
{
    use ResponseTrait;

    public function list(){
        $features = ServiceSetting::all();
        return datatables($features)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                return '<img src="' . getFileUrl($data->image) . '" alt="icon" class="rounded avatar-xs tbl-user-image w-25 h-32">';
            })

            ->addColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return '<div class="zBadge zBadge-active">'.__("Active").'</div>';
                } else {
                    return '<div class="zBadge zBadge-inactive">'.__("Deactivate").'</div>';
                }
            })
            ->addColumn('action', function ($data) {
                return '<div class="d-flex align-items-center g-10 justify-content-end">
                                <button onclick="getEditModal(\'' . route('super-admin.setting.service.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('assets/images/icon/edit-black.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteItem(\'' . route('super-admin.setting.service.delete', $data->id) . '\', \'serviceDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" title="Delete">
                                    <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                                </button>
                        </div>';
            })
            ->rawColumns(['status', 'image', 'action'])
            ->make(true);
    }

    public function serviceSettingStore($request){
        try {
            DB::beginTransaction();

            $id = $request->get('id', 0);
            $service = ServiceSetting::find($id);
            if (is_null($service)) {
                $service = new ServiceSetting();
            }
            $service->name = $request->name;
            $service->title = $request->title;
            $service->sub_title = $request->sub_title;
            $service->description = $request->description;
            $service->status = isset($request->status) ? $request->status : ACTIVE;

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('serviceSetting', $request->image);
                $service->image = $uploaded->id;
            }
            $service->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function serviceDelete($id)
    {
        try {
            $data = ServiceSetting::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

}

