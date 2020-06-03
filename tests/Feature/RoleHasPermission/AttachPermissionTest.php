<?php

namespace VCComponent\Laravel\User\Test\Feature\RoleHasPermission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Permission;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class AttachPermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_attach_permission_to_role()
    {
        $role           = factory(Role::class)->create();
        $permissions    = factory(Permission::class, 3)->create();
        $permission_ids = $permissions->map(function ($role) {
            return $role->id;
        });
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
    public function role_id_field_must_be_required()
    {
        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'role_id', 'The role id field is required.');
    }

    /**
     * @test
     */
    public function role_id_field_must_be_numeric()
    {
        $request = [
            'role_id' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'role_id', 'The role id must be a number.');
    }

    /**
     * @test
     */
    public function permission_ids_field_must_be_required()
    {
        $role    = factory(Role::class)->create();
        $request = [
            'role_id' => $role->id,
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'permission_ids', 'The permission ids field is required.');
    }

    /**
     * @test
     */
    public function permission_ids_field_must_be_array()
    {
        $role    = factory(Role::class)->create();
        $request = [
            'user_id'        => $role->id,
            'permission_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'permission_ids', 'The permission ids must be an array.');
    }

    /**
     * @test
     */
    public function permission_ids_field_item_must_be_numeric()
    {
        $role    = factory(Role::class)->create();
        $request = [
            'user_id'        => $role->id,
            'permission_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/permissions/attach', $request);

        $this->assertValidation($response, 'permission_ids.0', 'The permission_ids.0 must be a number.');
    }
}
