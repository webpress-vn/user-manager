<?php

namespace VCComponent\Laravel\User\Events;

use Illuminate\Queue\SerializesModels;

class UserDeletedEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
}
