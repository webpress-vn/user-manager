<?php

namespace VCComponent\Laravel\User\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\User\Auth\Auth as AuthHelper;
use VCComponent\Laravel\User\Contracts\AdminUserController;
use VCComponent\Laravel\User\Contracts\Auth;
use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\User\Contracts\FrontendUserController;
use VCComponent\Laravel\User\Contracts\UserValidatorInterface;
use VCComponent\Laravel\User\Http\Controllers\AuthController;
use VCComponent\Laravel\User\Providers\AdminController;
use VCComponent\Laravel\User\Providers\FrontendController;
use VCComponent\Laravel\User\Repositories\StatusRepository;
use VCComponent\Laravel\User\Repositories\StatusRepositoryEloquent;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Repositories\UserRepositoryEloquent;
use VCComponent\Laravel\User\Validators\AuthValidator;
use VCComponent\Laravel\User\Validators\UserValidator;

class LumenUserComponentProvider extends ServiceProvider
{
    private $adminController;
    private $frontendController;
    private $authController;
    private $userValidator;
    private $authValidator;
    private $auth;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('user.controllers.admin') === null) {
            $this->adminController = AdminController::class;
        } else {
            $this->adminController = config('user.controllers.admin');
        }

        if (config('user.controllers.frontend') === null) {
            $this->frontendController = FrontendController::class;
        } else {
            $this->frontendController = config('user.controllers.frontend');
        }

        if (config('user.controllers.auth') === null) {
            $this->authController = AuthController::class;
        } else {
            $this->authController = config('user.controllers.auth');
        }

        if (config('user.validators.user') === null) {
            $this->userValidator = UserValidator::class;
        } else {
            $this->userValidator = config('user.validators.user');
        }

        if (config('user.validators.auth') === null) {
            $this->userValidator = AuthValidator::class;
        } else {
            $this->userValidator = config('user.validators.auth');
        }

        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(StatusRepository::class, StatusRepositoryEloquent::class);
        $this->app->bind(AdminUserController::class, $this->adminController);
        $this->app->bind(FrontendUserController::class, $this->frontendController);
        $this->app->bind(Auth::class, $this->authController);
        $this->app->bind(UserValidatorInterface::class, $this->userValidator);
        $this->app->bind(AuthValidatorInterface::class, $this->authValidator);
        $this->app->bind('vcc.auth', AuthHelper::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->configure('user');
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/user.php', 'user'
        );
        $this->loadViewsFrom(
            __DIR__ . '/../../views', 'user_component'
        );
    }
}
