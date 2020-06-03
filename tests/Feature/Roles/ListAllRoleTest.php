<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class ListAllRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_list_all_roles()
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
    public function can_search_by_name_on_list_all_roles()
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
    public function can_search_by_slug_on_list_all_roles()
    {
        factory(Role::class, 10)->create();
        $role = factory(Role::class)->create([
            'name' => 'admin',
            'slug' => 'super.admin',
        ]);

        $response = $this->call('GET', 'api/user-management/admin/roles/all', ['search' => 'super']);

        $response->assertOk();
    }
}
