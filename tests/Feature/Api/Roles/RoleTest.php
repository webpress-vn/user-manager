<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\Roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

   /**
     * @test
     */
    public function can_create_role_by_admin_router()
    {
        $request = [];

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

        $response->assertStatus(422);
        $this->assertValidation($response, 'name', "The name field is required.");
        $this->assertValidation($response, 'slug', "The slug field is required.");

        $data = [
            'name' => 'Super admin',
            'slug' => 'super.admin',
        ];
        factory(Role::class)->create($data);
        $request = factory(Role::class)->make($data)->toArray();

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

        $response->assertStatus(422);
        $this->assertValidation($response, 'slug', "The slug has already been taken.");
        
        $data['slug'] = 'admin';
        $response     = $this->json('POST', 'api/user-management/admin/roles', $data);

        $this->assertDatabaseHas('roles', $data);
        $response->assertOk();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_delete_role_by_admin_router()
    {
        $role = factory(Role::class)->create();

        $response = $this->delete('api/user-management/admin/roles/' . $role->id);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    /**
     * @test
     */
    public function can_list_all_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();

        $response = $this->get('api/user-management/admin/roles/all');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
        ]);
        $this->assertCount(10, $response->decodeResponseJson()['data']);
    }

    /**
     * @test
     */
    public function can_search_by_name_on_list_all_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();
        $role = factory(Role::class)->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $response = $this->call('GET', 'api/user-management/admin/roles/all', ['search' => 'admi']);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_search_by_slug_on_list_all_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();
        $role = factory(Role::class)->create([
            'name' => 'admin',
            'slug' => 'super.admin',
        ]);

        $response = $this->call('GET', 'api/user-management/admin/roles/all', ['search' => 'super']);

        $response->assertOk();
    }

      /**
     * @test
     */
    public function can_list_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();

        $response = $this->get('api/user-management/admin/roles');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'meta' => ['pagination'],
        ]);
    }

    /**
     * @test
     */
    public function can_specify_per_page_on_list_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();

        $response = $this->call('GET', 'api/user-management/admin/roles', ['per_page' => 5]);

        $response->assertOk();
        $response->assertJson([
            'meta' => [
                'pagination' => [
                    'per_page' => 5,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_search_by_name_on_list_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();
        $role = factory(Role::class)->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $response = $this->call('GET', 'api/user-management/admin/roles', ['search' => 'admi']);

        $response->assertOk();
        $response->assertJson([
            'meta' => [
                'pagination' => [
                    'total' => 1,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_search_by_slug_on_list_roles_by_admin_router()
    {
        factory(Role::class, 10)->create();
        $role = factory(Role::class)->create([
            'name' => 'admin',
            'slug' => 'super.admin',
        ]);

        $response = $this->call('GET', 'api/user-management/admin/roles', ['search' => 'super']);

        $response->assertOk();
        $response->assertJson([
            'meta' => [
                'pagination' => [
                    'total' => 1,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_show_role_by_admin_router()
    {
        $role_data = factory(Role::class)->make()->toArray();
        $role      = factory(Role::class)->create($role_data);

        $response = $this->get('api/user-management/admin/roles/' . $role->id);

        $response->assertOk();
        $response->assertJson([
            'data' => $role_data,
        ]);
    }

    
    /**
     * @test
     */
    public function can_update_role_by_admin_router()
    {
        $data = [
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ];
        factory(Role::class)->create($data);

        $data = [
            'name' => 'Admin',
            'slug' => 'admin',
        ];
        $role = factory(Role::class)->create($data);

        $request = ['slug' => 'super.admin'];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $response->assertStatus(422);
        $this->assertValidation($response, 'slug', "The slug has already been taken.");

        $request = [
            'name'        => 'Super Admin Update',
            'slug'        => 'super.admin.update',
            'description' => 'Super admin role description',
            'level'       => 2,
        ];

        $response = $this->json('PUT', 'api/user-management/admin/roles/' . $role->id, $request);

        $check = array_merge($data, $request);
        $response->assertOk();
        $response->assertJson(['data' => $check]);
        $this->assertDatabaseHas('roles', $check);
    }
}
