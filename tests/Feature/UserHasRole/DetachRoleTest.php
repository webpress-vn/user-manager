<?php

namespace VCComponent\Laravel\User\Test\Feature\UserHasRole;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Test\TestCase;

class DetachRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_detach_role_to_user()
    {
        $user  = factory(User::class)->create();
        $roles = factory(Role::class, 3)->create();
        $this->attachRolesToUser($user, $roles);

        $role_ids = $roles->map(function ($role) {
            return $role->id;
        });
        $request = [
            'user_id'  => $user->id,
            'role_ids' => $role_ids->toArray(),
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $roles->each(function ($role) use ($user) {
            $this->assertDatabaseMissing('role_user', [
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        });
    }

    /**
     * @test
     */
    public function user_id_field_must_be_required()
    {
        $user    = factory(User::class)->create();
        $role    = factory(Role::class)->create();
        $request = [
            'role_id' => $role->id,
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'user_id', 'The user id field is required.');
    }

    /**
     * @test
     */
    public function user_id_field_must_be_numeric()
    {
        $user    = factory(User::class)->create();
        $role    = factory(Role::class)->create();
        $request = [
            'user_id' => 'abc',
            'role_id' => $role->id,
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'user_id', 'The user id must be a number.');
    }

    /**
     * @test
     */
    public function role_ids_field_must_be_required()
    {
        $user    = factory(User::class)->create();
        $role    = factory(Role::class)->create();
        $request = [
            'user_id' => $user->id,
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'role_ids', 'The role ids field is required.');
    }

    /**
     * @test
     */
    public function role_ids_field_must_be_array()
    {
        $user    = factory(User::class)->create();
        $role    = factory(Role::class)->create();
        $request = [
            'user_id'  => $user->id,
            'role_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'role_ids', 'The role ids must be an array.');
    }

    /**
     * @test
     */
    public function role_ids_field_item_must_be_numeric()
    {
        $user    = factory(User::class)->create();
        $role    = factory(Role::class)->create();
        $request = [
            'user_id'  => $user->id,
            'role_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'role_ids.0', 'The role_ids.0 must be a number.');
    }
}
