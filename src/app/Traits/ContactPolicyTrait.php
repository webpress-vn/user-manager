<?php

namespace VCComponent\Laravel\User\Traits;

class ContactPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-contact');
    }
}
