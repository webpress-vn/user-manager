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

        $token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6OSwiZW1haWwiOiJ0ZXN0QGdtYWlsLmNvbSIsImlhdCI6MTYzNTkzMTk1NywiZXhwIjo0MjI3OTMxOTU3fQ.ndiJq4bMWXi9X4K5Fm7Seqe2zOIw7UwFS575K081jmE";

        $response = $this->withHeader('Authorization', $token)->json('POST', '/api/user-management/connect');

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $request = [
            'email' => 'test@gmail.com',
        ];
        $this->assertDatabaseHas('users', $request);

    }
}
