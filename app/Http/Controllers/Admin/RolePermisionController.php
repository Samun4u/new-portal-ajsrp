<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Http\Services\ClientServices;
use App\Http\Services\RolePermissionService;
use App\Models\Currency;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermisionController extends Controller
{
    use ResponseTrait;

    public $rolePermissionServices;

    public function __construct()
    {
        $this->rolePermissionServices = new RolePermissionService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->rolePermissionServices->getRoleList();
        } else {
            $data['pageTitle'] = __('Roles & Permission');
            $data['activeSetting'] = 'active';
            $data['activeRolePermission'] = 'active';
            return view('admin.setting.role_permission.rolelist', $data);
        }
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Roles & Permission');
        $data['pageTitle'] = __('Add Roles');
        $data['activeSetting'] = 'active';
        $data['activeRolePermission'] = 'active';
        return view('admin.setting.role_permission.add-new', $data);
    }
    public function edit($id)
    {
        $data['pageTitleParent'] = __('Roles & Permission');
        $data['pageTitle'] = __('Edit Roles');
        $data['activeSetting'] = 'active';
        $data['activeRolePermission'] = 'active';
        $data['roleData'] = Role::find(decrypt($id));
        return view('admin.setting.role_permission.edit', $data);
    }
    public function permission($id)
    {
        $data['pageTitleParent'] = __('Roles & Permission');
        $data['pageTitle'] = __('Permission');
        $data['activeSetting'] = 'active';
        $data['activeRolePermission'] = 'active';
        $data['roleData'] = Role::find(decrypt($id));
        $data['permissionList'] = Permission::all();
        $data['rolePermissions'] = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",decrypt($id))
            ->get();
        return view('admin.setting.role_permission.permission', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20|unique:roles',
        ]);

        return $this->rolePermissionServices->store($request);
    }

    public function delete($id){
        try {
            DB::beginTransaction();
            $data = Role::find(decrypt($id));
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

    public function permissionUpdate(Request $request){
        $request->validate([
            'role' => 'required',
            'permission' => 'required',
        ]);
        return $this->rolePermissionServices->permissionUpdate($request);
    }
}
