<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use NF\Roles\Models\Permission;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\RoleHasPermission;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class AttachPermissionController extends ApiController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'role_id'          => ['required', 'numeric'],
            'permission_ids'   => ['required', 'array'],
            'permission_ids.*' => ['numeric'],
        ]);

        $role        = Role::findOrFail($request->input('role_id'));
        $permissions = Permission::findOrFail($request->input('permission_ids'));

        $permissions->each(function ($permission) use ($role) {
            RoleHasPermission::create([
                'role_id'       => $role->id,
                'permission_id' => $permission->id,
            ]);
        });

        return $this->success();
    }
}
