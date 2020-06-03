<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_update_role_name()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['name' => 'Super Admin Update'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }

    /**
     * @test
     */
    public function can_update_role_slug()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['slug' => 'super.admin.update'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }

    /**
     * @test
     */
    public function slug_field_must_be_unique()
    {
        factory(Role::class)->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['slug' => 'admin'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors"  => [
                "slug" => [
                    "The slug has already been taken.",
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function slug_field_must_be_unique_and_ignore_current_id()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['slug' => 'super.admin'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }

    /**
     * @test
     */
    public function can_update_role_description()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['description' => 'Super admin role description'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }

    /**
     * @test
     */
    public function can_update_role_level()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        $role    = factory(Role::class)->create($data);
        $request = ['level' => 2];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }
}
