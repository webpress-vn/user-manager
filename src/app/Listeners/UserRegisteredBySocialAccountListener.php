<?php

namespace VCComponent\Laravel\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use VCComponent\Laravel\User\Events\UserRegisteredBySocialAccountEvent;
use VCComponent\Laravel\User\Notifications\UserEmailVerifiedNotification;

class UserRegisteredBySocialAccountListener
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
     * @param  UserRegisteredBySocialAccountEvent  $event
     * @return void
     */
    public function handle(UserRegisteredBySocialAccountEvent $event)
    {
        $user = $event->user;
        // $user->notify(new UserEmailVerifiedNotification());
    }
}
