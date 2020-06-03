<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Entities\UserHasRole;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Transformers\UserHasRoleTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class DetachRoleController extends ApiController
{
    protected $userRepository;

    protected $userEntity;

    public function __construct(UserRepository $user_repository)
    {
        $this->userRepository = $user_repository;
        $this->userEntity     = $user_repository->getEntity();
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id'    => ['required', 'numeric'],
            'role_ids'   => ['required', 'array'],
            'role_ids.*' => ['numeric'],
        ]);

        $user     = $this->userEntity->findOrFail($request->input('user_id'));
        $roles    = Role::findOrFail($request->input('role_ids'));
        $role_ids = $roles->map(function ($role) {
            return $role->id;
        });

        UserHasRole::where('user_id', $user->id)
            ->whereIn('role_id', $role_ids->toArray())
            ->delete();

        return $this->success();
    }
}
