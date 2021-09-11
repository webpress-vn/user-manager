<?php

namespace VCComponent\Laravel\User\Traits;

class ContactFormValuePolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-contact-form-value');
    }
}
