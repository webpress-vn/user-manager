<?php

namespace VCComponent\Laravel\User\Traits;

class UserPolicyTrait
{
    public function ableToShow($user, $model)
    {
        return $user->can('view-user');
    }
    
    public function ableToCreate($user)
    {
        return $user->can('create-user');
    }

    public function ableToUpdateProfile($user, $model)
    {
        return $user->can('update-user-profile');
    }

    public function ableToUpdate($user, $model)
    {
        return $user->can('update-user');
    }

    public function ableToDelete($user, $model)
    {
        return $user->can('delete-user');
    }
}
