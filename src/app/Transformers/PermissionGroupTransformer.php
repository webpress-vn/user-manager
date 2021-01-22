<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;
use NF\Roles\Models\PermissionGroup;
use VCComponent\Laravel\User\Transformers\PermissionTransformer;

class PermissionGroupTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'permissions',
    ];

    public function transform(PermissionGroup $model)
    {

        return [
            'id'                        => (int) $model->id,
            'name'                      => $model->name,
            'slug'                      => $model->slug,
            'timestamps'                =>
            [
                'created_at'            => $model->created_at,
                'updated_at'            => $model->updated_at,
            ],

        ];
    }

    public function includePermissions(PermissionGroup $model)
    {
        return $this->collection($model->permissions, new PermissionTransformer);
    }
}
