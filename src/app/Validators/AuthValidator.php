<?php

namespace VCComponent\Laravel\User\Validators;

use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class AuthValidator extends AbstractValidator implements AuthValidatorInterface
{
    protected $rules = [
        'LOGIN'                => [
            'username' => ['required'],
            'password' => ['required', 'min:6'],
        ],
        'SOCIAL_LOGIN'         => [
            'provider'     => ['required'],
            'access_token' => ['required'],
        ],
        'RULE_UPDATE_AVATAR'   => [
            'avatar' => ['required'],
        ],
        'RULE_UPDATE_PASSWORD' => [
            'old_password'              => ['required'],
            'new_password'              => ['required', 'min:6', 'max:30'],
            'new_password_confirmation' => ['required'],
        ],
    ];
}
