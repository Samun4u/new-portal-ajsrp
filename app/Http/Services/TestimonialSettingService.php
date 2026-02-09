<?php

namespace App\Http\Services;

use App\Models\Testimonial;
use App\Traits\ResponseTrait;
use App\Models\FileManager;
use Exception;
use Illuminate\Support\Facades\DB;

class TestimonialSettingService
{
    use ResponseTrait;

    public function list()
    {
        $testimonials = Testimonial::Query();
        return datatables($testimonials)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                return '<img src="' . getFileUrl($data->image) . '" alt="icon" class="rounded avatar-xs tbl-user-image w-50 h-50">';
            })
            ->addColumn('status', function ($data) {
                if ($data->status == 1) {
                    return '<div class="zBadge zBadge-active">' . __('Active') . '</div>';
                } else {
                    return '<div class="zBadge zBadge-inactive">' . __('Deactivate') . '</div>';
                }
            })
            ->addColumn('action', function ($data) {
                return '
                    <div class="d-flex align-items-center g-10 justify-content-end">
                    <button type="button" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent"
                    onclick="getEditModal(\'' . route('super-admin.setting.testimonial.info', $data->id) . '\'' . ', \'#edit-modal\')"  data-id="' . $data->id . '" title="' . __('Edit') . '"><i class="fa-regular fa-pen-to-square"></i></button>
                    <button onclick="deleteItem(\'' . route('super-admin.setting.testimonial.delete', $data->id) . '\', \'testimonialDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" title="Delete">
                                    <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </div>';
            })
            ->rawColumns(['status', 'image', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        return Testimonial::findOrFail($id);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $testimonial = new Testimonial();
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->comment = $request->comment;
            $testimonial->status = isset($request->status) ? $request->status : ACTIVE;
            $testimonial->rating = $request->rating;
            $testimonial->company_name = $request->company_name;

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('testimonial', $request->image);
                $testimonial->image = $uploaded->id;
            }
            $testimonial->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->comment = $request->comment;
            $testimonial->status = $request->status;
            $testimonial->rating = $request->rating;
            $testimonial->company_name = $request->company_name;

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('testimonial', $request->image);
                $testimonial->image = $uploaded->id;
            }
            $testimonial->save();
            DB::commit();
            $message = getMessage(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $data = Testimonial::findOrFail($request->id);
            $file = FileManager::where('id', $data->image)->first();
            if ($file) {
                $file->removeFile();
                $file->delete();
            }
            $data->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

}
