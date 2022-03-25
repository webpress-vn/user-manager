<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Test\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use VCComponent\Laravel\User\Entities\User;

class UserConnectTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function can_connect_user_by_api_router()
    {
        $token = "";
        $response = $this->withHeader('Authorization', "Bearer " . $token)->json('POST', '/api/user-management/connect');
        $this->assertAuthorization($response);

        $data = [
            'sub' => '',
            'email' => 'test@gmail.com'
        ];
        
        $token = $this->fakeJWt($data);

        $response = $this->withHeader('Authorization', "Bearer " . $token)->json('POST', '/api/user-management/connect');

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $request = [
            'email' => 'test@gmail.com',
        ];
        $this->assertDatabaseHas('users', $request);
    }

    /**
     * @test
     */
    public function can_add_admin_role_to_connect_user_by_api_router()
    {
        $role = factory(Role::class)->create([
            'name' => 'admin',
            'slug'  => 'admin'
        ]);
        $data = [
            'sub' => '',
            'email' => 'test@gmail.com'
        ];

        $token = $this->fakeJWt($data);

        $response = $this->withHeader('Authorization', "Bearer " . $token)->json('POST', '/api/user-management/connect');

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $request = [
            'email' => 'test@gmail.com',
        ];
        $this->assertDatabaseHas('users', $request);

        $user = User::where('email', 'test@gmail.com')->first();

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function can_add_admin_role_to_old_user_by_api_router()
    {
        $role = factory(Role::class)->create([
            'name' => 'admin',
            'slug'  => 'admin'
        ]);

        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
            'verify_token'  => '',
            'username' => 'test'
        ]);

        $data = [
            'sub' => '',
            'email' => 'test@gmail.com'
        ];

        $token = $this->fakeJWt($data);

        $response = $this->withHeader('Authorization', "Bearer " . $token)->json('POST', '/api/user-management/connect');

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $user = User::where('email', 'test@gmail.com')->first();

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }

    public function fakeJWt($data)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        // Create token payload as a JSON string
        $payload = json_encode($data);
        
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
        
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        // Create JWT
        return $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
}
