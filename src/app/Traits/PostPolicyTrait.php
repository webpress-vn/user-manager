<?php

namespace VCComponent\Laravel\User\Traits;

class PostPolicyTrait
{
    public function ableToShow($user, $model)
    {
        return $user->can('view-post');
    }
    
    public function ableToCreate($user)
    {
        return $user->can('create-post');
    }

    public function ableToUpdateItem($user, $model)
    {
        return $user->can('update-item-post');
    }

    public function ableToUpdate($user, $model)
    {
        return $user->can('update-post');
    }

    public function ableToDelete($user, $model)
    {
        return $user->can('delete-post');
    }
}
