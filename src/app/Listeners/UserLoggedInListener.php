<?php

namespace VCComponent\Laravel\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use VCComponent\Laravel\User\Events\UserLoggedInEvent;

class UserLoggedInListener
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
     * @param  UserLoggedInEvent  $event
     * @return void
     */
    public function handle(UserLoggedInEvent $event)
    {

    }
}
