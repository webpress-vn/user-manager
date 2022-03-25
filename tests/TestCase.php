<?php

namespace VCComponent\Laravel\User\Test;

use Dingo\Api\Provider\LaravelServiceProvider;
use NF\Roles\RolesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Providers\UserComponentEventProvider;
use VCComponent\Laravel\User\Providers\UserComponentProvider;
use VCComponent\Laravel\User\Providers\UserComponentRouteProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return HaiCS\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            UserComponentProvider::class,
            UserComponentRouteProvider::class,
            UserComponentEventProvider::class,
            LaravelServiceProvider::class,
            RolesServiceProvider::class,
            \Laravel\Socialite\SocialiteServiceProvider::class,
            \Laravel\Passport\PassportServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../src/database/factories');
        // $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
        \Artisan::call('migrate',['-vvv' => true]);
        \Artisan::call('passport:install',['-vvv' => true]);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('passport.storage.database.connection', 'testbench');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('api', [
            'standardsTree'      => 'x',
            'subtype'            => '',
            'version'            => 'v1',
            'prefix'             => 'api',
            'domain'             => null,
            'name'               => null,
            'conditionalRequest' => true,
            'strict'             => false,
            'debug'              => true,
            'errorFormat'        => [
                'message'     => ':message',
                'errors'      => ':errors',
                'code'        => ':code',
                'status_code' => ':status_code',
                'debug'       => ':debug',
            ],
            'middleware'         => [
            ],
            'auth'               => [
            ],
            'throttling'         => [
            ],
            'transformer'        => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat'      => 'json',
            'formats'            => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions'     => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size'  => 2,
                ],
            ],
        ]);
        $app['config']->set('roles', [
            'connection' => null,
            'separator'  => '.',
            'models'     => [
                'role'       => \NF\Roles\Models\Role::class,
                'permission' => \NF\Roles\Models\Permission::class,
            ],
            'pretend'    => [
                'enabled' => false,
                'options' => [
                    'is'      => true,
                    'can'     => true,
                    'allowed' => true,
                ],
            ],
        ]);
        $app['config']->set('user', [
            'namespace'    => 'user-management',
            'transformers' => [
                'user' => \VCComponent\Laravel\User\Transformers\UserTransformer::class,
            ],
            'controllers'  => [
                'admin'    => \VCComponent\Laravel\User\Http\Controllers\Admin\UserController::class,
                'frontend' => \VCComponent\Laravel\User\Http\Controllers\Frontend\UserController::class,
                'auth'     => \VCComponent\Laravel\User\Http\Controllers\AuthController::class,
            ],
            'validators'   => [
                'user' => \VCComponent\Laravel\User\Validators\UserValidator::class,
                'auth' => \VCComponent\Laravel\User\Validators\AuthValidator::class,
            ],
            'test_mode'    => true,
        ]);
        $app['config']->set('roles', [
            'connection' => null,
            'separator' => '.',
            'models' => [
                'role' => \NF\Roles\Models\Role::class,
                'permission' => \NF\Roles\Models\Permission::class,
            ],
            'pretend' => [
                'enabled' => false,
                'options' => [
                    'is' => true,
                    'can' => true,
                    'allowed' => true,
                ],
            ],
        ]);
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('repository.cache.enabled', false);
        $app['config']->set('auth.guards', [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],   
            'api' => [
                'driver' => 'token',
                'provider' => 'users',
            ],
        ]);
    }

    public function assertValidation($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            "errors"  => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }

    public function assertAuthorization($response)
    {
        $response->assertStatus(401);
    }

    protected function attachRolesToUser($user, $roles)
    {
        $roles->each(function ($role) use ($user) {
            $user->attachRole($role);
        });
    }

    protected function attachPermissionsToRole($role, $permissions)
    {
        $permissions->each(function ($permission) use ($role) {
            $role->attachPermission($permission);
        });
    }
}
