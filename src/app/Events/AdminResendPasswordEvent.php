<?php

namespace VCComponent\Laravel\User\Events;

use Illuminate\Queue\SerializesModels;

class AdminResendPasswordEvent
{
    use SerializesModels;

    public $user;
    
    public $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user ,$token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
