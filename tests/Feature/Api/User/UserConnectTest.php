<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\User\Test\TestCase;

class UserConnectTest extends TestCase
{
    use RefreshDatabase;

    public function can_connect_user_by_api_router()
    {
        $token = "";
        $response = $this->withHeader('Authorization', $token)->json('POST', '/api/user-management/connect');
        $this->assertAuthorization($response);

        $data = [
            'sub' => '',
            'email' => 'test@gmail.com'
        ];
        $factory = JWTFactory::customClaims($data);
        $payload = $factory->make();
        $token = 'Bearer' . JWTAuth::encode($payload);

        $response = $this->withHeader('Authorization', $token)->json('POST', '/api/user-management/connect');

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $request = [
            'email' => 'test@gmail.com',
        ];
        $this->assertDatabaseHas('users', $request);

    }
}
