<?php

namespace VCComponent\Laravel\User\Validators;

use VCComponent\Laravel\User\Contracts\UserValidatorInterface;
use VCComponent\Laravel\User\Traits\UserValidatorTrait;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class UserValidator extends AbstractValidator implements UserValidatorInterface
{
    use UserValidatorTrait;

    protected $rules = [
        'ADMIN_CREATE_USER'     => [
            'email'      => ['required', 'email', 'max:40'],
            'password'   => ['required', 'min:6', 'max:30'],
            'username'   => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'first_name' => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'last_name'  => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
        ],
        'ADMIN_UPDATE_USER'     => [
            'email'      => ['required', 'email', 'max:40'],
            'username'   => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'first_name' => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'last_name'  => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
        ],
        'ADMIN_UPDATE_PASSWORD' => [
            'new_password'              => ['required', 'min:6', 'max:30'],
            'new_password_confirmation' => ['required', 'min:6', 'max:30'],
        ],
        'ADMIN_UPDATE_AVATAR'   => [
            'avatar' => ['required'],
        ],

        'RULE_CREATE'           => [
            'email'      => ['required', 'email', 'max:40'],
            'password'   => ['required', 'min:6', 'max:30'],
            'username'   => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'first_name' => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'last_name'  => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
        ],
        'RULE_UPDATE'           => [
            'username'   => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'first_name' => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
            'last_name'  => ['required', 'regex:/[a-z0-9\s]*/i', 'max:100'],
        ],
        'BULK_UPDATE_STATUS'    => [
            'item_ids' => ['required'],
            'status'   => ['required'],
        ],
        'UPDATE_STATUS_ITEM'    => [
            'status' => ['required'],
        ],
        'VERIFY_EMAIL'          => [
            'token' => ['required'],
        ],
    ];
}
