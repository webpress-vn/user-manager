<?php

return [

    'namespace'       => env('USER_MANAGEMENT_NAMESPACE', 'user-management'),

    'transformers'    => [
        'user' => VCComponent\Laravel\User\Transformers\UserTransformer::class,
    ],

    'controllers'     => [
        'admin'    => VCComponent\Laravel\User\Http\Controllers\Admin\UserController::class,
        'frontend' => VCComponent\Laravel\User\Http\Controllers\Frontend\UserController::class,
        'auth'     => VCComponent\Laravel\User\Http\Controllers\AuthController::class,
    ],

    'validators'      => [
        'user' => VCComponent\Laravel\User\Validators\UserValidator::class,
        'auth' => VCComponent\Laravel\User\Validators\AuthValidator::class,
    ],
    'test_mode' => false
];
