<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class ListRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_list_roles()
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
    public function can_specify_per_page_on_list_roles()
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
    public function can_search_by_name_on_list_roles()
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
    public function can_search_by_slug_on_list_roles()
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
}
