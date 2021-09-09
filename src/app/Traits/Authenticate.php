<?php

namespace VCComponent\Laravel\User\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use VCComponent\Laravel\User\Contracts\AuthValidatorInterface;
use VCComponent\Laravel\User\Events\UserLoggedInEvent;
use VCComponent\Laravel\User\Events\UserRegisteredBySocialAccountEvent;
use VCComponent\Laravel\User\Facades\VCCAuth;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Transformers\UserTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

trait Authenticate
{
    public function __construct(UserRepository $repository, AuthValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->entity     = $repository->getEntity();
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'socialLogin', 'saveOrUpdateUser', 'refresh']]);

        if (isset(config('user.transformers')['user'])) {
            $this->transformer = config('user.transformers.user');
        } else {
            $this->transformer = UserTransformer::class;
        }
    }

    public function authenticate(Request $request)
    {
        try {
            $user = VCCAuth::authenticate($request);

            $token = JWTAuth::fromUser($user);

            event(new UserLoggedInEvent($user));

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->response->array(compact('token'));
    }
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function invalidateToken() {
        try {
           return response()->json(JWTAuth::invalidate());

        } catch (JWTException $e) {
            return response()->json(['message' => 'Logout failed'], $e->getStatusCode());
        }
    }

    public function me(Request $request)
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                throw new NotFoundException('User');
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['message' => 'token expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['message' => 'token invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['message' => 'token absent'], $e->getStatusCode());

        }

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        Cache::flush();
        return $this->response->item($user, $transformer);
    }

    private function saveOrUpdateUser($social_account, $provider, $user_id = null)
    {
        $name_items = explode(' ', $social_account->name);
        $first_name = $name_items[0];
        unset($name_items[0]);
        $last_name = implode(' ', $name_items);

        if ($user_id != null) {
            $user = $this->entity->find($user_id);
        } else {
            $user               = $this->entity;
            $user->account_type = $provider;
            $user->social_id    = $social_account->getId() ? $social_account->getId() : '';
            $user->first_name   = $first_name;
            $user->last_name    = $last_name;

            $user->email = $social_account->getEmail() ? $social_account->getEmail() : $social_account->getId();

            if ($provider == 'facebook') {
                $user->avatar = $social_account->getAvatar() ? $social_account->getAvatar() . '&width=400&height=400' : '';
            } else {
                $user->avatar = $social_account->getAvatar() ? str_replace('sz=50', 'sz=400', $social_account->getAvatar()) : '';
            }

            $user->email_verified = 1;
            $user->save();
        }

        return $user;
    }

    public function socialLogin(Request $request)
    {
        $this->validator->isValid($request, 'SOCIAL_LOGIN');

        $provider       = $request->get('provider');
        $access_token   = $request->get('access_token');
        $social_account = Socialite::driver($provider)->userFromToken($access_token);

        $user             = null;
        $check_email_user = $this->entity->where('email', $social_account->getEmail())->first();
        if (!$check_email_user) {
            $check_user = $this->entity->where('account_type', $provider)->where('social_id', $social_account->getId())->first();
            if (!$check_user) {
                $user  = $this->saveOrUpdateUser($social_account, $provider);

                event(new UserRegisteredBySocialAccountEvent($user));

            } else {
                $user = $this->saveOrUpdateUser($social_account, $provider, $check_user['id']);
            }
        } else {
            $user = $this->saveOrUpdateUser($social_account, $provider, $check_email_user['id']);
        }

        $token = JWTAuth::fromUser($user);

        event(new UserLoggedInEvent($user));

        return $this->response->array(compact('token'));
    }

    public function avatar(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        $this->validator->isValid($request, 'RULE_UPDATE_AVATAR');

        $data = $request->only('avatar');
        $user = $this->repository->update($data, $user->id);

        return $this->success();
    }

    public function password(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        $this->validator->isValid($request, 'RULE_UPDATE_PASSWORD');

        $data = $request->only(['old_password', 'new_password', 'new_password_confirmation']);
        if (!Hash::check($data['old_password'], $user->password)) {
            throw new BadRequestHttpException('Old password does not match');
        }
        if ($data['new_password'] !== $data['new_password_confirmation']) {
            throw new BadRequestHttpException('Password is not confirmed');
        }

        $user->password = $data['new_password'];
        $user->save();

        return $this->success();
    }
}
