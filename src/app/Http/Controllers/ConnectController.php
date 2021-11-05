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
    protected $repository;
    protected $validator;
    protected $entity;
    protected $transformer;

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
            $token = JWTAuth::getToken();
            if (empty($token)) {
                throw new UnauthorizedHttpException('The Authorization data was invalid');
            }

            // $payload = JWTAuth::getPayload($token)->toArray();
            // $email = $payload['email'];

            $email = $this->JWTDecode($token);
            $user = $this->repository->firstOrCreate(
                [
                    'email' => $email,
                    'verify_token' => "",
                    'username' => explode('@', $email)[ 0 ]
                ]
            );
            $token = JWTAuth::fromUser($user);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return $this->response->array(compact('token'));
    }

    public function JWTDecode($token)
    {
        $object = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[ 1 ]))));
        $array = json_decode(json_encode($object), true);
        return $array[ 'email' ];
    }

}
