<?php

namespace VCComponent\Laravel\User\Events;

use Illuminate\Queue\SerializesModels;

class ResendVerifyEmailEvent
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
