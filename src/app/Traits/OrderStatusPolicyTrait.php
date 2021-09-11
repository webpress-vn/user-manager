<?php

namespace VCComponent\Laravel\User\Traits;

class OrderStatusPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-order-status');
    }
}
