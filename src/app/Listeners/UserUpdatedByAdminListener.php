<?php

namespace VCComponent\Laravel\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use VCComponent\Laravel\User\Events\UserUpdatedByAdminEvent;

class UserUpdatedByAdminListener
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
     * @param  UserCreatedByAdminEvent  $event
     * @return void
     */
    public function handle(UserUpdatedByAdminEvent $event)
    {

    }
}
