<?php

namespace App\Http\Services;

use App\Models\FeaturesSetting;
use App\Traits\ResponseTrait;
use App\Models\FileManager;
use Exception;
use Illuminate\Support\Facades\DB;

class FeaturesSettingService
{
    use ResponseTrait;

    public function list(){
        $features = FeaturesSetting::all();
        return datatables($features)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                return '<img src="' . getFileUrl($data->image) . '" alt="icon" class="rounded avatar-xs tbl-user-image w-25 h-25">';
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
                                <button onclick="getEditModal(\'' . route('super-admin.setting.features.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="Edit">
                                    <img src="' . asset('assets/images/icon/edit-black.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteItem(\'' . route('super-admin.setting.features.delete', $data->id) . '\', \'featuresDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" title="Delete">
                                    <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                                </button>
                        </div>';
            })
            ->rawColumns(['status', 'icon', 'image', 'action'])
            ->make(true);
    }

    public function featuresStore($request){
        try {
            DB::beginTransaction();

            $id = $request->get('id', 0);
            $features = FeaturesSetting::find($id);
            if (is_null($features)) {
                $features = new FeaturesSetting();
            }
            $features->title = $request->title;
            $features->description = $request->description;
            $features->status = isset($request->status) ? $request->status : ACTIVE;

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('featuresSetting', $request->image);
                $features->image = $uploaded->id;
            }

            $features->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function featuresDelete($id)
    {
        try {
            $data = FeaturesSetting::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

}
