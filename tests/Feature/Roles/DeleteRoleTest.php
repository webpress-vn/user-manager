<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_delete_role()
    {
        $role = factory(Role::class)->create();

        $response = $this->delete('api/user-management/admin/roles/' . $role->id);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseMissing('roles', $role->toArray());
    }
}
