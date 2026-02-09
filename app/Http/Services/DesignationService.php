<?php

namespace App\Http\Services;

use App\Models\Designation;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DesignationService
{
    use ResponseTrait;
    public function getAll()
    {
        return Designation::all();
    }

    public function getAllData()
    {
        $data = Designation::where('tenant_id', auth()->user()->tenant_id)->orderByDesc('id');
        return datatables($data)
            ->addIndexColumn()
            ->editColumn('title', function ($data) {
                return $data->title;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return "<p class='zBadge zBadge-active'>" .  __('Active') . "</p>";
                } else {
                    return "<p class='zBadge zBadge-inactive'>" .  __('Deactivate') . "</p>";
                }
            })
            ->addColumn('action', function ($data) {
                return "<div class='dropdown dropdown-one'>
                            <button
                                class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center'
                                type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='fa-solid fa-ellipsis'></i>
                            </button>
                            <ul class='dropdown-menu dropdownItem-two'>
                                <li>
                                    <a class='d-flex align-items-center cg-8' href='" . route('admin.setting.designation.edit', encrypt($data->id)) . "'>
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
                                    <a class='d-flex align-items-center cg-8 delete' type='button' data-url='" . route('admin.setting.designation.delete', encrypt($data->id)) . "'>
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
                            </ul>
                        </div>";
            })
            ->rawColumns(['title', 'status', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        return Designation::where('tenant_id', auth()->user()->tenant_id)->find(decrypt($id));
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $designation = Designation::where('tenant_id', auth()->user()->tenant_id)->findOrFail(decrypt($id));
            } else {
                $designation = new Designation();
            }
            $designation->user_id = auth()->id();
            $designation->tenant_id = auth()->user()->tenant_id;
            $designation->title = $request->title;
            $designation->slug = Str::slug($request->slug);
            $designation->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $designation->save();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

    public function deleteById($id)
    {
        try {
            $data = Designation::where('tenant_id', auth()->user()->tenant_id)->findOrFail(decrypt($id));
            $data->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __($e->getMessage()));
        }
    }
}
