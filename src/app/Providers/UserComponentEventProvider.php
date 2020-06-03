<?php

namespace VCComponent\Laravel\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use VCComponent\Laravel\User\Contracts\Events\UserCreatedByAdminEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserDeletedEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserEmailVerifiedEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserLoggedInEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserRegisteredBySocialAccountEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserRegisteredEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserUpdatedByAdminEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserUpdatedEventContract;
use VCComponent\Laravel\User\Events\UserCreatedByAdminEvent;
use VCComponent\Laravel\User\Events\UserDeletedEvent;
use VCComponent\Laravel\User\Events\UserEmailVerifiedEvent;
use VCComponent\Laravel\User\Events\UserLoggedInEvent;
use VCComponent\Laravel\User\Events\UserRegisteredBySocialAccountEvent;
use VCComponent\Laravel\User\Events\UserRegisteredEvent;
use VCComponent\Laravel\User\Events\UserUpdatedByAdminEvent;
use VCComponent\Laravel\User\Events\UserUpdatedEvent;

class UserComponentEventProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegisteredEvent::class                => [
            // UserRegisteredListener::class,
        ],
        UserEmailVerifiedEvent::class             => [
            // UserEmailVerifiedListener::class,
        ],
        UserLoggedInEvent::class                  => [
            // UserLoggedInListener::class,
        ],
        UserCreatedByAdminEvent::class            => [
            // UserCreatedByAdminListener::class,
        ],
        UserDeletedEvent::class                   => [
            // UserDeletedListener::class,
        ],
        UserUpdatedByAdminEvent::class            => [
            // UserUpdatedByAdminListener::class,
        ],
        UserUpdatedEvent::class                   => [
            // UserUpdatedListener::class,
        ],
        UserRegisteredBySocialAccountEvent::class => [
            // UserRegisteredBySocialAccountListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    public function register()
    {
        $this->app->bind(UserRegisteredEventContract::class, UserRegisteredEvent::class);
        $this->app->bind(UserEmailVerifiedEventContract::class, UserEmailVerifiedEvent::class);
        $this->app->bind(UserCreatedByAdminEventContract::class, UserCreatedByAdminEvent::class);
        $this->app->bind(UserLoggedInEventContract::class, UserLoggedInEvent::class);
        $this->app->bind(UserDeletedEventContract::class, UserDeletedEvent::class);
        $this->app->bind(UserUpdatedByAdminEventContract::class, UserUpdatedByAdminEvent::class);
        $this->app->bind(UserUpdatedEventContract::class, UserUpdatedEvent::class);
        $this->app->bind(UserRegisteredBySocialAccountEventContract::class, UserRegisteredBySocialAccountEvent::class);
    }
}
