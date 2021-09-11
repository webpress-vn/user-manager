<?php

namespace VCComponent\Laravel\User\Contracts;

interface UserPolicyInterface {
    public function ableToShow($user, $model);
    public function ableToCreate($user);
    public function ableToUpdateProfile($user, $model);
    public function ableToUpdate($user);
    public function ableToDelete($user, $model);
}