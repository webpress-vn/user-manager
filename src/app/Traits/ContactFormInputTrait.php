<?php

namespace VCComponent\Laravel\User\Traits;

class ContactFormInputPolicyTrait
{
    public function ableToUse($user, $model)
    {
        return $user->can('manage-contact-form-input');
    }
}
