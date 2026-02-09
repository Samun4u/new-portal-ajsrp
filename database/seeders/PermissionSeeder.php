<?php

namespace Database\Seeders;

use App\Http\Services\RolePermissionService;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    use ResponseTrait;

    public $rolePermissionServices;

    public function __construct()
    {
        $this->rolePermissionServices = new RolePermissionService();
    }

    public function run()
    {
        foreach (permissionArray() as $permission){
            Permission::insert(['name' => $permission, 'guard_name' => 'web']);
        }

        $user = User::where('role', USER_ROLE_ADMIN)->first();

        $dataObj = new Role();
        $dataObj->name = 'Admin';
        $dataObj->guard_name = 'web';
        $dataObj->user_id = $user->id;
        $dataObj->tenant_id = $user->tenant_id;
        $dataObj->status = STATUS_ACTIVE;
        $dataObj->save();

        $role = Role::find($dataObj->id);
        $role->syncPermissions(permissionArray());

        $user->syncRoles($role->id);
    }
}
