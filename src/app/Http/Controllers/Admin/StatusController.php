<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\User\Repositories\StatusRepository;
use VCComponent\Laravel\User\Traits\StatusMethodsAdmin;
use VCComponent\Laravel\User\Validators\StatusValidator;

class StatusController extends ApiController
{
    use StatusMethodsAdmin;

    protected $repository;
    protected $validator;

    public function __construct(StatusRepository $repository, StatusValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        if (config('user.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('user.auth_middleware.admin.middleware'),
                ['except' => config('user.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }
    }
}
