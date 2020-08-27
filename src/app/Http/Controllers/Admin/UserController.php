<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use VCComponent\Laravel\User\Contracts\AdminUserController;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\User\Traits\UserMethodsAdmin;

class UserController extends ApiController implements AdminUserController
{
    use UserMethodsAdmin;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
}
