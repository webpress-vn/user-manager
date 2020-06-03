<?php

namespace VCComponent\Laravel\User\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;
use VCComponent\Laravel\User\Contracts\Events\UserEmailVerifiedEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserRegisteredEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserUpdatedEventContract;
use VCComponent\Laravel\User\Contracts\UserValidatorInterface;
use VCComponent\Laravel\User\Facades\VCCAuth;
use VCComponent\Laravel\User\Notifications\UserRegisteredNotification;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Transformers\UserTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait UserMethodsFrontend
{
    public function __construct(UserRepository $repository, UserValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();
        $this->validator  = $validator;
        $this->middleware('jwt.auth', ['except' => [
            'index',
            'list',
            'show',
            'store',
            'verifyEmail',
            'isVerifiedEmail',
            'resendVerifyEmail',
        ]]);

        if (isset(config('user.transformers')['user'])) {
            $this->transformer = config('user.transformers.user');
        } else {
            $this->transformer = UserTransformer::class;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->entity;

        if ($request->has('roles')) {
            $query = $query->whereHas('roles', function ($q) use ($request) {
                $q->whereIn('slug', explode(',', $request->get('roles')));
            });
        }

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['email', 'username'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $users    = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }
        return $this->response->paginator($users, $transformer);
    }

    function list(Request $request) {
        $query = $this->entity;

        if ($request->has('roles')) {
            $query = $query->whereHas('roles', function ($q) use ($request) {
                $q->whereIn('slug', explode(',', $request->get('roles')));
            });
        }

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['email', 'username'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $users = $query->get();

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }
        return $this->response->collection($users, $transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data           = $this->filterRequestData($request, $this->repository);
        $schema_rules   = $this->validator->getSchemaRules($this->repository);
        $no_rule_fields = $this->validator->getNoRuleFields($this->repository);

        $this->validator->isValid($data['default'], 'RULE_CREATE');
        $this->validator->isSchemaValid($data['schema'], $schema_rules);
        $data['default']['verify_token'] = Hash::make($request->email);
        VCCAuth::isEmpty($request);

        $user = $this->repository->create($data['default']);

        $user->password = $data['default']['password'];
        if ($request->has('status')) {
            $user->status = $request->get('status');
        }
        $user->save();

        if (count($data['schema'])) {
            foreach ($data['schema'] as $key => $value) {
                $user->userMetas()->create([
                    'key'   => $key,
                    'value' => $value,
                ]);
            }
        }

        if (count($no_rule_fields)) {
            foreach ($no_rule_fields as $key => $value) {
                $user->userMetas()->updateOrCreate([
                    'key'   => $key,
                    'value' => null,
                ], ['value' => '']);
            }
        }

        // $user = $this->repository->attachRole('user', $user->id);

        $event = App::makeWith(UserRegisteredEventContract::class, ['user' => $user]);
        Event::dispatch($event);

        $token = JWTAuth::fromUser($user);

        return $this->response->array(compact('token'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToShow($id)) {
            throw new PermissionDeniedException();
        }

        $user = $this->repository->find($id);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->item($user, $transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToUpdateProfile($id)) {
            throw new PermissionDeniedException();
        }

        $data         = $this->filterRequestData($request, $this->repository);
        $schema_rules = $this->validator->getSchemaRules($this->repository);

        $this->validator->isValid($request, 'RULE_UPDATE');
        $this->validator->isSchemaValid($data['schema'], $schema_rules);

        VCCAuth::isExists($request, $id);

        $user = $this->repository->update($data['default'], $id);

        if (count($data['schema'])) {
            foreach ($data['schema'] as $key => $value) {
                $user->userMetas()->updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        $event = App::makeWith(UserUpdatedEventContract::class, ['user' => $user]);
        Event::dispatch($event);

        return $this->response->item($user, new $this->transformer);
    }

    public function verifyEmail(Request $request, $id)
    {
        $this->validator->isValid($request, 'VERIFY_EMAIL');

        $user = $this->repository->find($id);
        if (!Hash::check($user->email, $request->get('token'))) {
            throw new UnauthorizedHttpException("Token does not match", null, null, 1006);
        }
        $user = $this->repository->verifyEmail($user);

        $event = App::makeWith(UserEmailVerifiedEventContract::class, ['user' => $user]);
        Event::dispatch($event);

        return $this->response->item($user, new $this->transformer);
    }

    public function isVerifiedEmail($id)
    {
        $user = $this->repository->find($id);
        $data = [
            'email_verified'    => $user->email_verified == 1 ? true : false,
            'email_verified_at' => $user->email_verified_at = Carbon::now(),
        ];
        return $this->response->array(['data' => $data]);
    }

    public function resendVerifyEmail($id)
    {
        $user = $this->repository->find($id);

        if ($user->email_verified == 1) {
            throw new ConflictHttpException("Your email address is verified", null, 1007);
        }

        $user->notify(new UserRegisteredNotification());

        return $this->success();
    }
}
