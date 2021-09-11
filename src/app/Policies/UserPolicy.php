<?php

namespace VCComponent\Laravel\User\Policies;

use VCComponent\Laravel\User\Contracts\UserPolicyInterface;

class UserPolicy implements UserPolicyInterface 
{
    public function ableToShow($user, $model)
    {
        return true;
    }
    
    public function ableToCreate($user)
    {
        return true;
    }

    public function ableToUpdateProfile($user, $model)
    {
        return true;
    }

    public function ableToUpdate($user)
    {
        return true;
    }

    public function ableToDelete($user, $model)
    {
        return true;
    }
}