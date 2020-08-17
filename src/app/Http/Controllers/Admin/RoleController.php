<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use NF\Roles\Models\Role;
use VCComponent\Laravel\User\Transformers\RoleTransformer;
use VCComponent\Laravel\User\Validators\RoleValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class RoleController extends ApiController
{
    protected $validator;

    public function __construct(RoleValidator $validator)
    {
        $this->validator = $validator;
    }

    public function hasStatus($request, $query)
    {
        if ($request->has('status')) {
            $query = $query->where('status', $request->status);
        }
        return $query;
    }
    public function hasPermission($request, $query)
    {
        if ($request->has('permission')) {
            $query = $query->whereHas('permissions', function ($q) use ($request) {
                $q->whereIn('slug', explode(',', $request->get('permission')));
            });
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = Role::query();

        $query = $this->hasStatus($request, $query);
        $query = $this->hasPermission($request, $query);

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $roles    = $query->paginate($per_page);

        return $this->response->paginator($roles, new RoleTransformer());
    }

    function list(Request $request) {
        $query = Role::query();

        $query = $this->hasStatus($request, $query);
        $query = $this->hasPermission($request, $query);

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $roles    = $query->paginate($per_page);

        return $this->response->paginator($roles, new RoleTransformer());
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return $this->response->item($role, new RoleTransformer());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_ADMIN_CREATE');

        $role = Role::create($data);

        return $this->response->item($role, new RoleTransformer());
    }

    public function update($id, Request $request)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'slug' => [Rule::unique('roles')->ignore($id)],
        ]);

        $role->update($request->all());

        return $this->response->item($role, new RoleTransformer());
    }
    public function updateStatus(Request $request, $id)
    {
        $role = Role::find($id);

        $role->status = $request->input('status');
        $role->save();

        return $this->success();
    }
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $this->success();
    }
}
