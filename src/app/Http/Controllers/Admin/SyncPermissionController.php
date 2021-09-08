<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use NF\Roles\Models\Permission;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\RoleHasPermission;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class SyncPermissionController extends ApiController
{
    public function __construct()
    {
        if (config('user.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('user.auth_middleware.admin.middleware'),
                ['except' => config('user.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }
    }
    public function __invoke(Request $request)
    {
        $request->validate([
            'role_id'          => ['required', 'numeric'],
            'permission_ids'   => ['required', 'array'],
            'permission_ids.*' => ['numeric'],
        ]);

        $role        = Role::findOrFail($request->input('role_id'));
        $permissions = Permission::findOrFail($request->input('permission_ids'));

        $permissioniRoles = RoleHasPermission::where('role_id', $role->id)->get();
        if ($permissioniRoles !== null) {
            foreach ($permissioniRoles as $permissionRole) {
                $permissionRole->delete();
            }
        }

        $permissions->each(function ($permission) use ($role) {

            $data = [
                'role_id'       => $role->id,
                'permission_id' => $permission->id,
            ];

            RoleHasPermission::create($data);
        });

        return $this->success();
    }
}
