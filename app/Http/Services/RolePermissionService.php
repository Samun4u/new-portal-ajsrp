<?php

namespace App\Http\Services;

use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionService
{
    use ResponseTrait;

    public function getAll()
    {
        return Role::where('tenant_id', auth()->user()->tenant_id)->get();
    }

    public function getRoleList()
    {
        $data = Role::where('tenant_id', auth()->user()->tenant_id)->get();
        return datatables($data)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return "<p>$data->name</p>";
            })
            ->addColumn('action', function ($data) {
                if($data->id == 1){
                    return "<div class='dropdown dropdown-one'>
                        <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>

                        <li><a class='d-flex align-items-center cg-8' href='".route('admin.setting.role-permission.permission', encrypt($data->id))."'>
                        <div class='d-flex'><svg width='15' height='15' viewBox='0 0 15 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M15 11.521V15H11.25V13.125H9.375V11.25H7.5V9.73389C7.13867 9.92432 6.76025 10.0684 6.36475 10.166C5.96924 10.2637 5.56641 10.3125 5.15625 10.3125C4.68262 10.3125 4.22607 10.2515 3.78662 10.1294C3.34717 10.0073 2.93701 9.83398 2.55615 9.60938C2.17529 9.38477 1.82861 9.11621 1.51611 8.80371C1.20361 8.49121 0.932617 8.14209 0.703125 7.75635C0.473633 7.37061 0.300293 6.96045 0.183105 6.52588C0.065918 6.09131 0.00488281 5.63477 0 5.15625C0 4.68262 0.0610352 4.22607 0.183105 3.78662C0.305176 3.34717 0.478516 2.93701 0.703125 2.55615C0.927734 2.17529 1.19629 1.82861 1.50879 1.51611C1.82129 1.20361 2.17041 0.932617 2.55615 0.703125C2.94189 0.473633 3.35205 0.300293 3.78662 0.183105C4.22119 0.065918 4.67773 0.00488281 5.15625 0C5.62988 0 6.08643 0.0610352 6.52588 0.183105C6.96533 0.305176 7.37549 0.478516 7.75635 0.703125C8.13721 0.927734 8.48389 1.19629 8.79639 1.50879C9.10889 1.82129 9.37988 2.17041 9.60938 2.55615C9.83887 2.94189 10.0122 3.35205 10.1294 3.78662C10.2466 4.22119 10.3076 4.67773 10.3125 5.15625C10.3125 5.40527 10.293 5.65186 10.2539 5.896C10.2148 6.14014 10.1587 6.37939 10.0854 6.61377L15 11.521ZM3.75 5.15625C3.94531 5.15625 4.12842 5.11963 4.29932 5.04639C4.47021 4.97314 4.61914 4.87305 4.74609 4.74609C4.87305 4.61914 4.97314 4.47021 5.04639 4.29932C5.11963 4.12842 5.15625 3.94531 5.15625 3.75C5.15625 3.55469 5.11963 3.37158 5.04639 3.20068C4.97314 3.02979 4.87305 2.88086 4.74609 2.75391C4.61914 2.62695 4.47021 2.52686 4.29932 2.45361C4.12842 2.38037 3.94531 2.34375 3.75 2.34375C3.55469 2.34375 3.37158 2.38037 3.20068 2.45361C3.02979 2.52686 2.88086 2.62695 2.75391 2.75391C2.62695 2.88086 2.52686 3.02979 2.45361 3.20068C2.38037 3.37158 2.34375 3.55469 2.34375 3.75C2.34375 3.94531 2.38037 4.12842 2.45361 4.29932C2.52686 4.47021 2.62695 4.61914 2.75391 4.74609C2.88086 4.87305 3.02979 4.97314 3.20068 5.04639C3.37158 5.11963 3.55469 5.15625 3.75 5.15625Z' fill='#5D697A'/></svg></div>
                        <p class='fs-14 fw-500 lh-17 text-para-text'>".__('Add Permission')."</p></a></li>

                        </ul></div>";
                }else{
                return "<div class='dropdown dropdown-one'>
                        <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button><ul class='dropdown-menu dropdownItem-two'>
                        <li>
                        <a class='d-flex align-items-center cg-8' href='".route('admin.setting.role-permission.edit', encrypt($data->id))."'>
                        <div class='d-flex'>
                        <svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' /></svg>
                        </div>
                            <p class='fs-14 fw-500 lh-17 text-para-text'>".__('Edit')."</p></a>
                        </li>
                        <li><button class='d-flex align-items-center cg-8 border-0 p-0 bg-transparent' onclick='deleteItem(\"" . route('admin.setting.role-permission.delete', encrypt($data->id)) . "\", \"roleListTable\")'>
                        <div class='d-flex'><svg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd'clip-rule='evenodd'd='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'fill='#5D697A'/></svg></div>
                        <p class='fs-14 fw-500 lh-17 text-para-text'>".__('Delete')."</p></button></li>

                        <li><a class='d-flex align-items-center cg-8' href='".route('admin.setting.role-permission.permission', encrypt($data->id))."'>
                        <div class='d-flex'><svg width='15' height='15' viewBox='0 0 15 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M15 11.521V15H11.25V13.125H9.375V11.25H7.5V9.73389C7.13867 9.92432 6.76025 10.0684 6.36475 10.166C5.96924 10.2637 5.56641 10.3125 5.15625 10.3125C4.68262 10.3125 4.22607 10.2515 3.78662 10.1294C3.34717 10.0073 2.93701 9.83398 2.55615 9.60938C2.17529 9.38477 1.82861 9.11621 1.51611 8.80371C1.20361 8.49121 0.932617 8.14209 0.703125 7.75635C0.473633 7.37061 0.300293 6.96045 0.183105 6.52588C0.065918 6.09131 0.00488281 5.63477 0 5.15625C0 4.68262 0.0610352 4.22607 0.183105 3.78662C0.305176 3.34717 0.478516 2.93701 0.703125 2.55615C0.927734 2.17529 1.19629 1.82861 1.50879 1.51611C1.82129 1.20361 2.17041 0.932617 2.55615 0.703125C2.94189 0.473633 3.35205 0.300293 3.78662 0.183105C4.22119 0.065918 4.67773 0.00488281 5.15625 0C5.62988 0 6.08643 0.0610352 6.52588 0.183105C6.96533 0.305176 7.37549 0.478516 7.75635 0.703125C8.13721 0.927734 8.48389 1.19629 8.79639 1.50879C9.10889 1.82129 9.37988 2.17041 9.60938 2.55615C9.83887 2.94189 10.0122 3.35205 10.1294 3.78662C10.2466 4.22119 10.3076 4.67773 10.3125 5.15625C10.3125 5.40527 10.293 5.65186 10.2539 5.896C10.2148 6.14014 10.1587 6.37939 10.0854 6.61377L15 11.521ZM3.75 5.15625C3.94531 5.15625 4.12842 5.11963 4.29932 5.04639C4.47021 4.97314 4.61914 4.87305 4.74609 4.74609C4.87305 4.61914 4.97314 4.47021 5.04639 4.29932C5.11963 4.12842 5.15625 3.94531 5.15625 3.75C5.15625 3.55469 5.11963 3.37158 5.04639 3.20068C4.97314 3.02979 4.87305 2.88086 4.74609 2.75391C4.61914 2.62695 4.47021 2.52686 4.29932 2.45361C4.12842 2.38037 3.94531 2.34375 3.75 2.34375C3.55469 2.34375 3.37158 2.38037 3.20068 2.45361C3.02979 2.52686 2.88086 2.62695 2.75391 2.75391C2.62695 2.88086 2.52686 3.02979 2.45361 3.20068C2.38037 3.37158 2.34375 3.55469 2.34375 3.75C2.34375 3.94531 2.38037 4.12842 2.45361 4.29932C2.52686 4.47021 2.62695 4.61914 2.75391 4.74609C2.88086 4.87305 3.02979 4.97314 3.20068 5.04639C3.37158 5.11963 3.55469 5.15625 3.75 5.15625Z' fill='#5D697A'/></svg></div>
                        <p class='fs-14 fw-500 lh-17 text-para-text'>".__('Add Permission')."</p></a></li>

                        </ul></div>";
                }

            })
            ->rawColumns(['name', 'action', 'status'])
            ->make(true);

    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $dataObj = Role::find(decrypt($request->id));
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $dataObj = new Role();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $dataObj->name = $request->name;
            $dataObj->guard_name = 'web';
            $dataObj->user_id = auth()->id();
            $dataObj->tenant_id = auth()->user()->tenant_id;
            $dataObj->status = STATUS_ACTIVE;
            $dataObj->save();

            DB::commit();
            return $this->success([], $msg);

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], $exception->getMessage());
        }
    }
    public function permissionUpdate($request)
    {
        try {
            $role = Role::find(decrypt($request->role));
            $role->syncPermissions($request->permission);
            return $this->success([], UPDATED_SUCCESSFULLY);
        } catch (Exception $exception) {
            return $this->error([], SOMETHING_WENT_WRONG);
        }
    }

}
