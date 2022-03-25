<?php

namespace VCComponent\Laravel\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use NF\Roles\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

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

        if (isset(config('user.transformers')['user'])) {
            $this->transformer = config('user.transformers.user');
        } else {
            $this->transformer = UserTransformer::class;
        }
    }

    public function connect(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (empty($token)) {
                throw new UnauthorizedHttpException('The Authorization data was invalid');
            }

            $email = $this->JWTDecode($token);
            $user = $this->repository->firstOrCreate(
                [
                    'email' => $email,
                    'verify_token' => "",
                    'username' => explode('@', $email)[ 0 ]
                ]
            );
            $this->attachAdminRole($user);
            $token = $user->createToken(['auth'])->accessToken;
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['message' => 'The Authorization data was invalid', 'status_code' => 401], 401);
        } catch (\Exception $e) {
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

    protected function attachAdminRole($user) {
        if (!$user->isAdministrator()) {
            $admin_role = Role::whereIn('slug', ['admin', 'super_admin'])->first();
            if($admin_role) {
                $user->attachRole($admin_role->id);
            }
        }
    }

}
