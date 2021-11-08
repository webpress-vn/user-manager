<?php

namespace VCComponent\Laravel\User\Contracts;

use Illuminate\Http\Request;
use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\User\Repositories\UserRepository;

interface Auth
{
    public function __construct(UserRepository $repository, AuthValidatorInterface $validator);
    public function authenticate(Request $request);
    public function refresh();
    public function invalidateToken();
    public function me(Request $request);
    public function socialLogin(Request $request);
}
