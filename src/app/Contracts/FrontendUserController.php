<?php

namespace VCComponent\Laravel\User\Contracts;

use Illuminate\Http\Request;
use VCComponent\Laravel\User\Contracts\UserValidatorInterface;
use VCComponent\Laravel\User\Repositories\UserRepository;

interface FrontendUserController
{
    public function __construct(UserRepository $repository, UserValidatorInterface $validator);
    public function index(Request $request);
    public function list(Request $request);
    public function show(Request $request, $id);
    public function store(Request $request);
    public function update(Request $request, $id);
    public function verifyEmail(Request $request, $id);
    public function isVerifiedEmail($id);
    public function resendVerifyEmail($id);
}
