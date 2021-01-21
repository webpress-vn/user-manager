<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\UserHasRole;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Test\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_attach_role_to_user_by_admin_router()
    {
        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/roles/attach', $request);

        $this->assertValidation($response, 'role_ids', 'The role ids field is required.');
        $this->assertValidation($response, 'user_id', 'The user id field is required.');

        $request = [
            'user_id'  => 'abc',
            'role_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/attach', $request);

        $this->assertValidation($response, 'user_id', 'The user id must be a number.');
        $this->assertValidation($response, 'role_ids', 'The role ids must be an array.');

        $user    = factory(User::class)->create();
        $request = [
            'user_id'  => $user->id,
            'role_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/attach', $request);

        $this->assertValidation($response, 'role_ids.0', 'The role_ids.0 must be a number.');

        $roles    = factory(Role::class, 3)->create();
        $role_ids = $roles->map(function ($role) {
            return $role->id;
        });

        $request = [
            'user_id'  => $user->id,
            'role_ids' => $role_ids->toArray(),
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/attach', $request);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $roles->each(function ($role) use ($user) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        });
    }

    /**
     * @test
     */
    public function can_detach_role_to_user_by_admin_router()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'user_id', 'The user id field is required.');
        $this->assertValidation($response, 'role_ids', 'The role ids field is required.');

        $request = [
            'user_id'  => 'abc',
            'role_ids' => 'abc',
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'user_id', 'The user id must be a number.');
        $this->assertValidation($response, 'role_ids', 'The role ids must be an array.');

        $request = [
            'user_id'  => $user->id,
            'role_ids' => ['sdfsdf'],
        ];

        $response = $this->json('POST', 'api/user-management/admin/roles/detach', $request);

        $this->assertValidation($response, 'role_ids.0', 'The role_ids.0 must be a number.');

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

    // /**
    //  * @test
    //  */
    // public function can_sync_role_to_user_by_admin_router()
    // {
    //     $dataLogin = ['email' => 'integrationTest@gmail.com', 'password'=> '123456789&'];
    //     $admin  = factory(User::class)->create();
       
    //     $request = [];

    //     $response = $this->json('POST', 'api/user-management/admin/roles/sync', $request);

    //     $this->assertValidation($response, 'user_id', 'The user id field is required.');
    //     $this->assertValidation($response, 'role_ids', 'The role ids field is required.');


    //     $user  = factory(User::class)->create();
    //     $roles = factory(Role::class, 3)->create();

    //     $this->attachRolesToUser($user, $roles);
    //     // dd('ko');
    //     $response = $this->actingAs($admin)->get('api/user-management/admin/users/'. $user->id. '?include=roles');
    //     dd($response->Json());
    // }
}
