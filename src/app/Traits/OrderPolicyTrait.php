<?php

namespace VCComponent\Laravel\User\Traits;

class OrderPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-order');
    }
}
