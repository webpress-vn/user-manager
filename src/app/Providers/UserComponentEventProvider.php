<?php

namespace VCComponent\Laravel\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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
    }
}
