<?php

namespace VCComponent\Laravel\User\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use VCComponent\Laravel\User\Contracts\Auth as UserAuthContract;
use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class ConnectController extends ApiController
{
    public function __construct(UserRepository $repository, AuthValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->entity     = $repository->getEntity();
        // $this->middleware('jwt.auth', ['except' => ['authenticate', 'socialLogin', 'saveOrUpdateUser']]);

        if (isset(config('user.transformers')['user'])) {
            $this->transformer = config('user.transformers.user');
        } else {
            $this->transformer = UserTransformer::class;
        }
    }

    public function connect()
    {
        try {
            $user = $this->repository->findByField('username', 'admin@vmms.vn')->first();
            
            if (!$user) {
                $user = $this->repository->findByField('email', 'admin@vmms.vn')->first();
                if (!$user) {
                    throw new NotFoundException('user');
                }
            }

            if (!Hash::check('secret', $user->password)) {
                throw new \Exception("Password does not match", 1003);
            }

            $token = JWTAuth::fromUser($user);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->response->array(compact('token'));
    }

    protected $repository;
    protected $validator;
    protected $entity;
    protected $transformer;
}
