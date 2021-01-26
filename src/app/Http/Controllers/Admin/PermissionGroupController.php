<?php

namespace VCComponent\Laravel\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use NF\Roles\Models\PermissionGroup;
use VCComponent\Laravel\User\Transformers\PermissionGroupTransformer;
use VCComponent\Laravel\User\Validators\PermissionGroupValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class PermissionGroupController extends ApiController
{
    protected $validator;

    public function __construct(PermissionGroupValidator $validator)
    {
        $this->validator = $validator;
    }

    public function index(Request $request)
    {
        $query = PermissionGroup::query();
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $permissionGroup    = $query->paginate($per_page);
        return $this->response->paginator($permissionGroup, new PermissionGroupTransformer());
    }

    function list(Request $request) {
        $query = PermissionGroup::query();
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name', 'slug'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->query('per_page') : 15;
        $permissionGroup    = $query->paginate($per_page);
        return $this->response->paginator($permissionGroup, new PermissionGroupTransformer());
    }

    public function show($id)
    {
        $role = PermissionGroup::findOrFail($id);
        return $this->response->item($role, new PermissionGroupTransformer());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_ADMIN_CREATE');

        $permissionGroup = PermissionGroup::create($data);

        return $this->response->item($permissionGroup, new PermissionGroupTransformer());
    }

    public function update($id, Request $request)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);

        $request->validate([
            'slug' => [Rule::unique('roles')->ignore($id)],
        ]);

        $permissionGroup->update($request->all());

        return $this->response->item($permissionGroup, new PermissionGroupTransformer());
    }
    public function destroy($id)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        $permissionGroup->delete();
        return $this->success();
    }
}
