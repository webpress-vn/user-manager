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
        $this->middleware('jwt.auth', ['except' => []]);
    }
}
