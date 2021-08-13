<?php

namespace VCComponent\Laravel\User\Test\Feature\Web\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Notifications\AdminResendPasswordNotification;
use VCComponent\Laravel\User\Test\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_verify_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->call('GET', 'verify/' . $user->id);

        $response->assertOk();
        $response->assertViewIs('userTest::errow-verify');

        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user      = factory(User::class)->make($dataLogin);
        $user->save();

        $login = $this->json('POST', 'api/user-management/login', $dataLogin);
        $token = $login->Json()['token'];

    }

    /**
     * @test
     */
    public function can_get_verify_not_me_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->call('GET', 'verify-not-me/' . $user->id);

        $response->assertOk();
        $response->assertViewIs('userTest::errow-verify');
    }

    /**
     * @test
     */
    public function can_send_email_forgot_password_by_web_router()
    {
        $user = factory(User::class)->create();

        $homePage = 'http://localhost';
        $success  = "We have emailed your password reset link!";

        $request = ['email' => $user->email];
        Notification::fake();
        $response = $this->call('POST', 'forgot-password', $request);

        $response->assertRedirect($homePage);
        $response->assertSessionHas('status', $success);
    }

    /**
     * @test
     */
    public function can_get_reset_password_screen_by_web_router()
    {
        $user = $this->loginToken();

        $response = $this->call('GET', 'reset-password');

        $response->assertViewIs('userTest::reset-password');
    }

    /**
     * @test
     */
    public function can_post_reset_password_by_web_router()
    {
        $token = $this->loginToken();

        $request = [
            'token'                 => $token,
            'email'                 => 'admin@test.com',
            'password'              => '123456789',
            'password_confirmation' => '1234567899',
        ];
        $response = $this->call('POST', 'reset-password', $request);

        $response->assertJson([
            'errors' =>
            ['password' =>
                ['The password confirmation does not match.'],
            ],
        ]);

        $request = [
            'token'                 => $token,
            'email'                 => 'admin@test.com',
            'password'              => '123456789',
            'password_confirmation' => '123456789',
        ];

        $response = $this->call('POST', 'reset-password', $request);

        $homePage = 'http://localhost';
        $response->assertRedirect($homePage);
        $response->assertSessionHas('errors');
    }

    /**
     * @test
     */
    public function can_get_account_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->call('GET', 'account');

        $response->assertOk();
        $response->assertViewIs('userTest::account');
    }

    /**
     * @test
     */
    public function can_get_login_screen_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->call('GET', 'login');

        $response->assertOk();
        $response->assertViewIs('userTest::login');

        $response = $this->actingAs($user)->call('GET', 'login');

        $homePage = 'http://localhost/home';
        $response->assertRedirect($homePage);
    }

    /**
     * @test
     */
    public function can_post_login_screen_by_web_router()
    {
        $dataLogin = ['username' => 'customer@test.com', 'password' => '123456789'];

        $user = factory(User::class)->make($dataLogin);
        $user->save();

        $response  = $this->call('GET', 'account');
        $loginPage = 'http://localhost/login';
        $response->assertRedirect($loginPage);

        $response   = $this->call('POST', 'login', $dataLogin);
        $acountPage = 'http://localhost/account';
        $response->assertRedirect($acountPage);

        $response = $this->call('GET', 'account');
        $response->assertOk();
        $response->assertViewIs('userTest::account');
    }

    /**
     * @test
     */
    public function can_logout_screen_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user);
        $response = $this->call('GET', 'account');

        $response->assertOk();
        $response->assertViewIs('userTest::account');

        $response = $this->call('GET', 'logout');

        $homePage = 'http://localhost';
        $response->assertRedirect($homePage);

        $response = $this->call('GET', 'account');

        $loginPage = 'http://localhost/login';

        $response->assertRedirect($loginPage);
    }

    /**
     * @test
     */
    public function can_get_register_screen_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->call('GET', 'register');

        $response->assertOk();
        $response->assertViewIs('userTest::registration');
    }

    /**
     * @test
     */
    public function can_get_forgot_password_screen_by_web_router()
    {
        $user = factory(User::class)->create();

        $response = $this->call('GET', 'forgot-password');

        $response->assertOk();
        $response->assertViewIs('userTest::forgot-password');
    }

    /**
     * @test
     */
    public function can_post_register_by_web_router()
    {
        $request = [
            'email'                 => 'customer@gmail.com',
            'username'              => 'customer',
            'password'              => '123456789',
            'phone_number'          => '098164849',
            'password_confirmation' => '123456789',
            'first_name'            => 'first_name',
            'last_name'             => 'last_name',
            'address'               => 'address',

        ];
        $response = $this->call('POST', 'register', $request);

        $homePage = 'http://localhost';
        $response->assertRedirect($homePage);
        
        $response = $this->call('GET', 'account');

        $response->assertOk();
        $response->assertViewIs('userTest::account');
    }

     /**
     * @test
     */
    public function can_edit_info_by_web_router()
    {
        $user = factory(User::class)->create();
         $request = [
            'email'        => 'customerUpdate@gmail.com',
            'phone_number' => '098164849Update',
            'first_name'   => 'first_nameUpdate',
            'last_name'    => 'last_nameUpdate',
            'address'      => 'addressUpdate',
            'gender'       => '1',
            'auth_id'      => $user->id
        ];

        $response = $this->actingAs($user)->call('POST', 'info-edit', $request);

        unset($request['auth_id']);
        $response->assertSessionHas('messages', "Thay đổi thông tin cá nhân thành công !");
        $this->assertDatabaseHas('users', $request);
    }

    protected function loginToken()
    {
        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user      = factory(User::class)->make($dataLogin);
        $user->save();

        $login = $this->json('POST', 'api/user-management/login', $dataLogin);
        $token = $login->Json()['token'];

        return $token;
    }
}
