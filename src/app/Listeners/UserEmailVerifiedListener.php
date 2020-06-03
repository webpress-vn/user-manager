<?php

namespace VCComponent\Laravel\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use VCComponent\Laravel\User\Events\UserEmailVerifiedEvent;
use VCComponent\Laravel\User\Notifications\UserEmailVerifiedNotification;

class UserEmailVerifiedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserEmailVerifiedEvent  $event
     * @return void
     */
    public function handle(UserEmailVerifiedEvent $event)
    {
        $user = $event->user;
        // $user->notify(new UserEmailVerifiedNotification());
    }
}
