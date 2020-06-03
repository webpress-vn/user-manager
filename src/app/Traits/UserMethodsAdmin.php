<?php

namespace VCComponent\Laravel\User\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use VCComponent\Laravel\User\Contracts\Events\UserCreatedByAdminEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserDeletedEventContract;
use VCComponent\Laravel\User\Contracts\Events\UserUpdatedByAdminEventContract;
use VCComponent\Laravel\User\Contracts\UserValidatorInterface;
use VCComponent\Laravel\User\Exports\UserExports;
use VCComponent\Laravel\User\Facades\VCCAuth;
use VCComponent\Laravel\User\Notifications\AdminResendPasswordNotification;
use VCComponent\Laravel\User\Notifications\AdminResendVerifiedNotification;
use VCComponent\Laravel\User\Repositories\UserRepository;
use VCComponent\Laravel\User\Transformers\UserTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait UserMethodsAdmin
{
    public function __construct(UserRepository $repository, UserValidatorInterface $validator, UserExports $exports)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();
        $this->validator  = $validator;
        $this->exports    = $exports;
        $this->middleware('jwt.auth', ['except' => []]);

        if (isset(config('user.transformers')['user'])) {
            $this->transformer = config('user.transformers.user');
        } else {
            $this->transformer = UserTransformer::class;
        }
    }

    public function hasVrifyRequest($request, $query)
    {
        if ($request->has('verify')) {
            $query = $query->where('email_verified', $request->verify);
        }
        return $query;
    }

    public function hasStatus($request, $query)
    {
        if ($request->has('status')) {
            $query = $query->where('status', $request->status);
        }
        return $query;
    }


    public function hasRole($request, $query)
    {
        if ($request->has('roles')) {
            $query = $query->whereHas('roles', function ($q) use ($request) {
                $q->whereIn('slug', explode(',', $request->get('roles')));
            });
        }
        return $query;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['email', 'username'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $query = $this->hasRole($request, $query);
        $query = $this->hasStatus($request, $query);
        $query = $this->hasVrifyRequest($request, $query);

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

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['email', 'username'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $query = $this->hasRole($request, $query);
        $query = $this->hasStatus($request, $query);
        $query = $this->hasVrifyRequest($request, $query);

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
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToCreate()) {
            throw new PermissionDeniedException();
        }

        $data           = $this->filterRequestData($request, $this->repository);
        $schema_rules   = $this->validator->getSchemaRules($this->repository);
        $no_rule_fields = $this->validator->getNoRuleFields($this->repository);

        $this->validator->isValid($data['default'], 'ADMIN_CREATE_USER');

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

        $event = App::makeWith(UserCreatedByAdminEventContract::class, ['user' => $user]);
        Event::dispatch($event);

        return $this->response->item($user, new $this->transformer);
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

        $this->validator->isValid($data['default'], 'ADMIN_UPDATE_USER');
        $this->validator->isSchemaValid($data['schema'], $schema_rules);

        VCCAuth::isExists($request, $id);

        $user = $this->repository->update($data['default'], $id);

        if ($request->has('status')) {
            $user->status = $request->get('status');
            $user->save();
        }

        if (count($data['schema'])) {
            foreach ($data['schema'] as $key => $value) {
                $user->userMetas()->updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        $event = App::makeWith(UserUpdatedByAdminEventContract::class, ['user' => $user]);
        Event::dispatch($event);

        return $this->response->item($user, new $this->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToDelete($id)) {
            throw new PermissionDeniedException();
        }

        $this->repository->delete($id);

        $event = App::make(UserDeletedEventContract::class);
        Event::dispatch($event);

        return $this->success();
    }

    public function bulkUpdateStatus(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToUpdate()) {
            throw new PermissionDeniedException();
        }

        $this->validator->isValid($request, 'BULK_UPDATE_STATUS');

        $data = $request->all();

        $query = $this->entity;
        $query->whereIn('id', $data['item_ids'])->update(['status' => $data['status']]);

        return $this->success();
    }

    public function status(Request $request, $id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user->ableToUpdate()) {
            throw new PermissionDeniedException();
        }

        $this->validator->isValid($request, 'UPDATE_STATUS_ITEM');

        $data = $request->all();

        $query = $this->entity;
        $query->where('id', $id)->update(['status' => $data['status']]);

        return $this->success();
    }

    public function changePassword(Request $request, $id)
    {
        $user = $this->entity->find($id);
        if (!$user) {
            throw new NotFoundException('User');
        }

        $this->validator->isValid($request, 'ADMIN_UPDATE_PASSWORD');

        $data = $request->only(['new_password', 'new_password_confirmation']);
        if ($data['new_password'] !== $data['new_password_confirmation']) {
            throw new BadRequestHttpException('The password and confirmation do not match');
        }

        $user->password = $data['new_password'];
        $user->save();

        return $this->success();
    }

    public function avatar(Request $request, $id)
    {
        $user = $this->entity->find($id);
        if (!$user) {
            throw new NotFoundException('User');
        }

        $this->validator->isValid($request, 'ADMIN_UPDATE_AVATAR');

        $data = $request->only('avatar');

        $user = $this->repository->update($data, $id);

        return $this->success();
    }

    //admin verify
    public function resendVerifyEmail(Request $request, $id)
    {

        $user = $this->repository->find($id);

        if ($user->email_verified === 1) {
            throw new ConflictHttpException("Your email address is verified");
        }

        $user->notify(new AdminResendVerifiedNotification());
        return $this->success();
    }

    public function resendPassword($id)
    {
        $user           = $this->repository->find($id);
        $pass           = Str::random(6);
        $user->password = $pass;
        $user->update();

        $user->notify(new AdminResendPasswordNotification($pass));

        return $this->success();
    }

    public function verifyEmail($id)
    {
        $user = $this->repository->find($id);

        if ($user->email_verified === 1) {
            throw new ConflictHttpException("Your email address is verified");
        }
        $user = $this->repository->verifyEmail($user);
        $user->save();

        return $this->success();
    }

    public function exportExcel(Request $request)
    {
        $users = $this->entity;

        $users = $this->hasRole($request, $users);
        $users = $this->hasStatus($request, $users);
        $users = $this->hasVrifyRequest($request, $users);
        $users = $users->get();

        Excel::store(new $this->exports($users), 'users.xlsx', 'excel');

        return Response()->download(public_path('exports/users.xlsx'));
    }
}
