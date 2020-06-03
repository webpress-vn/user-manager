<?php

namespace VCComponent\Laravel\User\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UserLoggedInEvent
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
