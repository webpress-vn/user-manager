<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\UserHasRole;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Transformers\UserHasRoleTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class AttachRoleController extends ApiController
{
    protected $userRepository;

    protected $userEntity;

    public function __construct(UserRepository $user_repository)
    {
        $this->userRepository = $user_repository;
        $this->userEntity     = $user_repository->getEntity();
        if (config('user.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('user.auth_middleware.admin.middleware'),
                ['except' => config('user.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id'    => ['required', 'numeric'],
            'role_ids'   => ['required', 'array'],
            'role_ids.*' => ['numeric'],
        ]);

        $user  = $this->userEntity->findOrFail($request->input('user_id'));
        $roles = Role::findOrFail($request->input('role_ids'));

        $roles->each(function ($role) use ($user) {
            UserHasRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        });

        return $this->success();
    }
}
