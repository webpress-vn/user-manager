<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Test\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_login()
    {
        $dataLogin = ['username' => 'admin@test.com', 'password' => '123456789'];
        $user      = factory(User::class)->make($dataLogin);
        // dd($user);
        $user->save();

        $response = $this->json('POST', 'api/user-management/login', $dataLogin);
        dd($response->decodeResponseJson());

        // $response->dump();
    }
}
