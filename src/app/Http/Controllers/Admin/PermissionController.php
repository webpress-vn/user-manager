<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use NF\Roles\Models\Permission;
use VCComponent\Laravel\User\Transformers\PermissionTransformer;
use VCComponent\Laravel\User\Validators\PermissionValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class PermissionController extends ApiController
{
    protected $validator;

    public function __construct(PermissionValidator $validator)
    {
        $this->validator = $validator;
    }

    public function index(Request $request)
    {
        $query = Permission::query();

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $permissions    = $query->paginate($per_page);

        return $this->response->paginator($permissions, new PermissionTransformer());
    }

    function list(Request $request) {
        $query = Permission::query();

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $permissions    = $query->paginate($per_page);

        return $this->response->paginator($permissions, new PermissionTransformer());
    }

    public function show($id)
    {
        $role = Permission::findOrFail($id);
        return $this->response->item($role, new PermissionTransformer());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_ADMIN_CREATE');

        $permissions = Permission::create($data);

        return $this->response->item($permissions, new PermissionTransformer());
    }

    public function update($id, Request $request)
    {
        $permissions = Permission::findOrFail($id);

        $request->validate([
            'slug' => [Rule::unique('roles')->ignore($id)],
        ]);

        $permissions->update($request->all());

        return $this->response->item($permissions, new PermissionTransformer());
    }
    public function destroy($id)
    {
        $permissions = Permission::findOrFail($id);
        $permissions->delete();
        return $this->success();
    }
}
