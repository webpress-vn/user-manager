<?php

namespace VCComponent\Laravel\User\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class UserComponentRouteProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        $router->aliasMiddleware('JWTAuth', \Tymon\JWTAuth\Http\Middleware\Authenticate::class);
        $router->aliasMiddleware('JWTFactory', \Tymon\JWTAuth\Facades\JWTFactory::class);
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
