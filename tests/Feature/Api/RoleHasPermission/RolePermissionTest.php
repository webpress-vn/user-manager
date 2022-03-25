<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\RoleHasPermission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use NF\Roles\Models\Permission;
use NF\Roles\Models\PermissionGroup;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_attach_permission_to_role_by_admin_router()
    {
        $role           = factory(Role::class)->create();
        $permissions    = factory(Permission::class, 3)->create();
        $permission_ids = $permissions->map(function ($role) {
            return $role->id;
        });

        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'role_id', 'The role id field is required.');
        $this->assertValidation($response, 'permission_ids', 'The permission ids field is required.');
       
        $request = [
            'role_id' => 'abc',
            'permission_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'role_id', 'The role id must be a number.');
        $this->assertValidation($response, 'permission_ids', 'The permission ids must be an array.');

         $request = [
            'user_id'        => $role->id,
            'permission_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'permission_ids.0', 'The permission_ids.0 must be a number.');
        
        $request = [
            'role_id'        => $role->id,
            'permission_ids' => $permission_ids->toArray(),
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $permissions->each(function ($permission) use ($role) {
            $this->assertDatabaseHas('permission_role', [
                'role_id'       => $role->id,
                'permission_id' => $permission->id,
            ]);
        });
    }

     /**
     * @test
     */
    public function can_detach_permission_from_role_by_admin_router()
    {
        $role        = factory(Role::class)->create();
        $permission_group = PermissionGroup::create(['name'=> 'haha', 'slug' => 'haha']);
        $permissions = factory(Permission::class, 3)->create();
        $this->attachPermissionsToRole($role, $permissions);

        $permission_ids = $permissions->map(function ($permission) {
            return $permission->id;
        });

        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/permissions/detach', $request);

        $this->assertValidation($response, 'role_id', 'The role id field is required.');
        $this->assertValidation($response, 'permission_ids', 'The permission ids field is required.');

        $request = [
            'role_id'        => 'abc',
            'permission_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/detach', $request);

        $this->assertValidation($response, 'role_id', 'The role id must be a number.');
        $this->assertValidation($response, 'permission_ids', 'The permission ids must be an array.');

        $request = [
            'user_id'        => $role->id,
            'permission_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/detach', $request);

        $this->assertValidation($response, 'permission_ids.0', 'The permission_ids.0 must be a number.');

        $request = [
            'role_id'        => $role->id,
            'permission_ids' => $permission_ids->toArray(),
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/detach', $request);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $permissions->each(function ($permission) use ($role) {
            $this->assertDatabaseMissing('permission_role', [
                'role_id'       => $role->id,
                'permission_id' => $permission->id,
            ]);
        });
    }
}