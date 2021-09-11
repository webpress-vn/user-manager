<?php

namespace VCComponent\Laravel\User\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class UserAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('show-user', 'VCComponent\Laravel\User\Contracts\UserPolicyInterface@ableToShow');
        Gate::define('create-user', 'VCComponent\Laravel\User\Contracts\UserPolicyInterface@ableToCreate');
        Gate::define('update-user-profile', 'VCComponent\Laravel\User\Contracts\UserPolicyInterface@ableToUpdateProfile');
        Gate::define('update-user', 'VCComponent\Laravel\User\Contracts\UserPolicyInterface@ableToUpdate');
        Gate::define('delete-user', 'VCComponent\Laravel\User\Contracts\UserPolicyInterface@ableToDelete');
        //
    }
}
