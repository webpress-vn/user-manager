<?php

namespace VCComponent\Laravel\User\Traits;

class CategoryPolicyTrait
{
    public function ableToShow($user, $model)
    {
        return $user->can('view-category');
    }
    
    public function ableToCreate($user)
    {
        return $user->can('create-category');
    }

    public function ableToUpdate($user, $model)
    {
        return $user->can('update-category');
    }

    public function ableToDelete($user, $model)
    {
        return $user->can('delete-category');
    }
}
