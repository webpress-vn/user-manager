<?php

namespace VCComponent\Laravel\User\Traits;

class ContactFormPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-contact-form');
    }
}
