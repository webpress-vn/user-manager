<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use NF\Roles\Models\Permission;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\RoleHasPermission;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class DetachPermissionController extends ApiController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'role_id'          => ['required', 'numeric'],
            'permission_ids'   => ['required', 'array'],
            'permission_ids.*' => ['numeric'],
        ]);

        $role           = Role::findOrFail($request->input('role_id'));
        $permissions    = Permission::findOrFail($request->input('permission_ids'));
        $permission_ids = $permissions->map(function ($permission) {
            return $permission->id;
        });

        RoleHasPermission::where('role_id', $role->id)
            ->whereIn('permission_id', $permission_ids->toArray())
            ->delete();

        return $this->success();
    }
}
