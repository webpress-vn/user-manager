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
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
