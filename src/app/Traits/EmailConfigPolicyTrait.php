<?php

namespace VCComponent\Laravel\User\Traits;

class EmailConfigPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-email-config');
    }
}
