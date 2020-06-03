<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class ShowRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_show_role()
    {
        $role_data = factory(Role::class)->make()->toArray();
        $role      = factory(Role::class)->create($role_data);

        $response = $this->get('api/user-management/admin/roles/' . $role->id);

        $response->assertOk();
        $response->assertJson([
            'data' => $role_data,
        ]);
    }
}
