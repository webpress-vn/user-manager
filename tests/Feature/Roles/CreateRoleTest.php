<?php

namespace VCComponent\Laravel\User\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;

class CreateRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_role()
    {
        $request = factory(Role::class)->make([
            'name' => 'Super Admin',
            'slug' => 'super.admin',
        ])->toArray();

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

        $this->assertDatabaseHas('roles', $request);
        $response->assertOk();
        $response->assertJson(['data' => $request]);
    }

    /**
     * @test
     */
    public function name_parameter_must_be_required()
    {
        $request = factory(Role::class)->make([
            'name' => '',
        ])->toArray();

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors"  => [
                "name" => [
                    "The name field is required.",
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function slug_parameter_must_be_required()
    {
        $request = factory(Role::class)->make([
            'name' => 'Super admin',
            'slug' => '',
        ])->toArray();

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors"  => [
                "slug" => [
                    "The slug field is required.",
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function slug_parameter_must_be_unique()
    {
        $data = [
            'name' => 'Super admin',
            'slug' => 'super.admin',
        ];
        factory(Role::class)->create($data);
        $request = factory(Role::class)->make($data)->toArray();

        $response = $this->json('POST', 'api/user-management/admin/roles', $request);

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
}
