<?php

namespace VCComponent\Laravel\User\Policies;

use VCComponent\Laravel\User\Contracts\UserPolicyInterface;

class UserPolicy implements UserPolicyInterface 
{
    public function before($user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    public function view($user, $model)
    {
        return $user->hasPermission('view-user');
    }
    
    public function create($user)
    {
        return $user->hasPermission('create-user');
    }

    public function updateProfile($user, $model)
    {
        return $user->hasPermission('update-user-profile');
    }

    public function update($user)
    {
        return $user->hasPermission('update-user');
    }

    public function delete($user, $model)
    {
        return $user->hasPermission('delete-user');
    }
}