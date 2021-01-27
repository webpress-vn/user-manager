<?php

namespace VCComponent\Laravel\User\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class PermissionValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_ADMIN_CREATE' => [
            'name' => ['required'],
            'slug' => ['required', 'unique:pesmissions,slug'],
        ],
    ];
}
