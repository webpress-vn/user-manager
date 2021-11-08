<?php

namespace VCComponent\Laravel\User\Test\Feature\Api\User;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Entities\UserHasRole;
use VCComponent\Laravel\User\Test\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_register_by_web_api_router()
    {
        $request  = [];
        $response = $this->json('POST', 'api/user-management/register', $request);

        $this->assertValidation($response, 'email', "The email field is required.");
        $this->assertValidation($response, 'password', "The password field is required.");
        $this->assertValidation($response, 'username', "The username field is required.");
        $this->assertValidation($response, 'first_name', "The first name field is required.");
        $this->assertValidation($response, 'last_name', "The last name field is required.");

        $request = [
            'email'      => 'email',
            'password'   => 'password',
            'username'   => 'username',
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
        ];

        $response = $this->json('POST', 'api/user-management/register', $request);

        $this->assertValidation($response, 'email', "The email must be a valid email address.");

        $request['email'] = 'test@gmail.com';

        $response = $this->json('POST', 'api/user-management/register', $request);

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        unset($request['password']);
        $this->assertDatabaseHas('users', $request);
    }

    /**
     * @test
     */
    public function can_login_by_web_api_router()
    {
        $dataLogin = ['username' => 'admin@test.com', 'password' => '123456789'];
        $user      = factory(User::class)->make($dataLogin);
        $user->save();

        $response = $this->json('POST', 'api/user-management/login', $dataLogin);
        $response->assertOk();
        $response->assertJsonStructure(['token']);
    }

    /**
     * @test
     */
    public function can_get_me_by_web_api_router()
    {
        $token = $this->loginToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/me');

        $response->assertOk();
        $response->assertJson(['data' => ['username' => 'admin', 'email' => 'admin@test.com']]);
    }

    /**
     * @test
     */
    public function can_update_avatar_me_by_web_api_router()
    {
        $token    = $this->loginToken();
        $request  = [];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/me/avatar', $request);
        $this->assertValidation($response, 'avatar', "The avatar field is required.");

        $request  = ['avatar' => 'avatarTest'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/me/avatar', $request);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_change_password_me_by_web_api_router()
    {
        $token    = $this->loginToken();
        $request  = [];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/me/password', $request);
        $this->assertValidation($response, 'old_password', "The old password field is required.");
        $this->assertValidation($response, 'new_password', "The new password field is required.");
        $this->assertValidation($response, 'new_password_confirmation', "The new password confirmation field is required.");

        $request = [
            'old_password'              => '123456789',
            'new_password'              => 'password',
            'new_password_confirmation' => '091828421',

        ];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/me/password', $request);

        $response->assertJson(['message' => "Password is not confirmed"]);

        $request['new_password_confirmation'] = $request['new_password'];
        $response                             = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/me/password', $request);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_get_reset_link_email_by_web_api_router()
    {
        $token    = $this->loginToken();
        $request  = [];
        $response = $this->json('POST', 'api/user-management/password/email', $request);

        $this->assertValidation($response, 'email', "The email field is required.");

        $request  = ['email' => 'test', 'reset_password_url' => ''];
        $response = $this->json('POST', 'api/user-management/password/email', $request);

        $this->assertValidation($response, 'email', "The email must be a valid email address.");

        $request  = ['email' => 'test@gmail.com', 'reset_password_url' => 'reset_password_url'];
        $response = $this->json('POST', 'api/user-management/password/email', $request);

        $response->assertJson(['message' => 'Email not found']);

        $user = factory(User::class)->create();

        $request = ['email' => $user->email, 'reset_password_url' => 'reset_password_url'];

        Mail::fake();
        $response = $this->json('POST', 'api/user-management/password/email', $request);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_reset_password_by_web_api_router()
    {
        $request  = [];
        $response = $this->json('PUT', 'api/user-management/password/reset', $request);

        $this->assertValidation($response, 'token', "The token field is required.");
        $this->assertValidation($response, 'email', "The email field is required.");
        $this->assertValidation($response, 'password', "The password field is required.");

        $request = [
            'token'    => 'asfasf',
            'email'    => 'test',
            'password' => '1234456',
        ];
        $response = $this->json('PUT', 'api/user-management/password/reset', $request);

        $this->assertValidation($response, 'email', "The email must be a valid email address.");
        $this->assertValidation($response, 'password', "The password confirmation does not match.");

        $user = factory(User::class)->create();

        $token = app(PasswordBroker::class)->createToken($user);

        $request = [
            'token'                 => $token,
            'email'                 => 'test@email.com',
            'password'              => '1234456',
            'password_confirmation' => '1234456',
        ];
        $response = $this->json('PUT', 'api/user-management/password/reset', $request);

        $response->assertJson(['message' => "Token doesn't match or expired"]);

        $user = factory(User::class)->create();

        $token = app(PasswordBroker::class)->createToken($user);

        $request = [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => '1234456',
            'password_confirmation' => '1234456',
        ];

        $response = $this->json('PUT', 'api/user-management/password/reset', $request);

        $response->assertOk();
        $response->assertJsonStructure(['token' => []]);
    }

    /**
     * @test
     */
    public function can_send_verify_email_by_admin_api_router()
    {
        $user  = factory(User::class)->create();
        $token = $this->loginToken();

        $customer = factory(User::class)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/user-management/admin/users/' . $customer->id . '/resend-verify-email');

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_verify_email_by_admin_api_router()
    {
        $user  = factory(User::class)->create();
        $token = $this->loginToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', "api/user-management/admin/users/{$user->id}/verify-email");

        $data                   = $user->toArray();
        $data['email_verified'] = true;

        unset($data['updated_at']);
        unset($data['created_at']);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     */
    public function can_resend_email_by_admin_api_router()
    {
        $user  = factory(User::class)->create();
        $token = $this->loginToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', "api/user-management/admin/users/{$user->id}/verify-email");

        $data                   = $user->toArray();
        $data['email_verified'] = true;

        unset($data['updated_at']);
        unset($data['created_at']);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     */
    public function can_resend_password_by_admin_api_router()
    {
        $token = $this->loginToken();

        $customer = factory(User::class)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer->id . '/resend-password');

        $this->assertValidation($response, 'reset_password_url', 'The reset password url field is required.');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer->id . '/resend-password', ['reset_password_url' => 'the_reset_password_url']);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_update_avatar_customer_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create();

        $request  = [];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer->id . '/avatar', $request);
        $this->assertValidation($response, 'avatar', "The avatar field is required.");

        $request  = ['avatar' => 'avatar'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer->id . '/avatar', $request);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function can_get_users_list_by_admin_api_router()
    {
        $token     = $this->loginToken();
        $customers = factory(User::class, 5)->create();

        $customers = $customers->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users');

        $response->assertOk();
        $response->assertJsonStructure([
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        foreach ($customers as $item) {
            $this->assertDatabaseHas('users', $item);
        }
    }

    /**
     * @test
     */
    public function can_get_users_list_with_no_paginate_by_admin_api_router()
    {
        $token     = $this->loginToken();
        $customers = factory(User::class, 5)->create();

        $customers = $customers->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/all');

        $response->assertOk();
        $response->assertJsonMissingExact([
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

        foreach ($customers as $item) {
            $this->assertDatabaseHas('users', $item);
        }
    }

    /**
     * @test
     */
    public function can_create_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $request  = [];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/user-management/admin/users', $request);

        $this->assertValidation($response, 'email', "The email field is required.");
        $this->assertValidation($response, 'password', "The password field is required.");
        $this->assertValidation($response, 'username', "The username field is required.");
        $this->assertValidation($response, 'first_name', "The first name field is required.");
        $this->assertValidation($response, 'last_name', "The last name field is required.");

        $request = [
            'email'      => 'email',
            'password'   => 'password',
            'username'   => 'username',
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/user-management/admin/users', $request);

        $this->assertValidation($response, 'email', "The email must be a valid email address.");

        $request['email'] = 'test@gmail.com';

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/user-management/admin/users', $request);

        $response->assertOk();
        $response->assertJson(['data' => [
            'email'      => $request['email'],
            'username'   => $request['username'],
            'first_name' => $request['first_name'],
            'last_name'  => $request['last_name'],
        ]]);
    }

    /**
     * @test
     */
    public function can_show_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/' . $customer->id);

        $response->assertOk();
        $response->assertJson(['data' => [
            'email'      => $customer->email,
            'username'   => $customer->username,
            'first_name' => $customer->first_name,
            'last_name'  => $customer->last_name,
        ]]);
    }

    /**
     * @test
     */
    public function can_update_a_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/' . $customer->id);

        $response->assertOk();
        $response->assertJson(['data' => [
            'email'      => $customer->email,
            'username'   => $customer->username,
            'first_name' => $customer->first_name,
            'last_name'  => $customer->last_name,
        ]]);

        $request = [
            'email'      => 'update@gmail.com',
            'username'   => 'updateName',
            'first_name' => 'nameFirst',
            'last_name'  => 'nameLast',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer->id, $request);

        $response->assertOk();
        $response->assertJson(['data' => [
            'email'    => $request['email'],
            'username' => $request['username'],
        ]]);
    }

    /**
     * @test
     */
    public function can_delete_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create()->toArray();

        unset($customer['updated_at']);
        unset($customer['created_at']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', 'api/user-management/admin/users/' . $customer['id']);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDeleted('users', $customer);
    }

    /**
     * @test
     */
    public function can_bulk_update_status_users_by_admin_api_router()
    {
        $token     = $this->loginToken();
        $customers = factory(User::class, 5)->create();

        $customers = $customers->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $listIds = array_column($customers, 'id');
        $data    = ['ids' => $listIds, 'status' => 5];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/all');

        $response->assertJsonFragment(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/status/bulk', $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/all');
        $response->assertJsonFragment(['status' => 5]);
    }

    /**
     * @test
     */
    public function can_update_status_a_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create()->toArray();

        unset($customer['updated_at']);
        unset($customer['created_at']);

        $request = ['status' => 5];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/' . $customer['id']);

        $response->assertJson(['data' => ['status' => 0]]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer['id'] . '/status', $request);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/user-management/admin/users/' . $customer['id']);
        $response->assertJsonFragment(['status' => 5]);
    }

    /**
     * @test
     */
    public function can_change_password_a_user_by_admin_api_router()
    {
        $token    = $this->loginToken();
        $customer = factory(User::class)->create()->toArray();

        unset($customer['updated_at']);
        unset($customer['created_at']);

        $request = [];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer['id'] . '/password', $request);

        $this->assertValidation($response, 'new_password', "The new password field is required.");
        $this->assertValidation($response, 'new_password_confirmation', "The new password confirmation field is required.");

        $request = [
            'new_password'              => 'new_password',
            'new_password_confirmation' => 'new_password_confirmation',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer['id'] . '/password', $request);

        $response->assertJson(['message' => 'The password and confirmation do not match']);

        $request = [
            'new_password'              => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/user-management/admin/users/' . $customer['id'] . '/password', $request);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

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

    protected function loginToken()
    {
        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user      = factory(User::class)->make($dataLogin);
        $user->save();
        $user->attachRole(factory(Role::class)->create(['slug' => 'admin']));
        $login = $this->json('POST', 'api/user-management/login', $dataLogin);
        $token = $login->Json()['token'];

        return $token;
    }
}
