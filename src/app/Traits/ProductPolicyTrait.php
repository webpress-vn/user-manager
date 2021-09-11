<?php

namespace VCComponent\Laravel\User\Traits;

class ProductPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-product');
    }

    public function ableToShow($user, $model)
    {
        return $user->can('view-product');
    }
    
    public function ableToCreate($user)
    {
        return $user->can('create-product');
    }

    public function ableToUpdateItem($user, $model)
    {
        return $user->can('update-item-product');
    }

    public function ableToUpdate($user, $model)
    {
        return $user->can('update-product');
    }

    public function ableToDelete($user, $model)
    {
        return $user->can('delete-product');
    }
}