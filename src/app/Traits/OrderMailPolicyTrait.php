<?php

namespace VCComponent\Laravel\User\Traits;

class OrderMailPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-order-mail');
    }
}
