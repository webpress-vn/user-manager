<?php

namespace VCComponent\Laravel\User\Traits;

class OrderItemPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-order-item');
    }
}
