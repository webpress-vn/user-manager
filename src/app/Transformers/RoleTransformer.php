<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\User\Transformers\PermissionTransformer;

class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'permissions',
        'users',
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'          => (int) $model->id,
            'name'        => $model->name,
            'slug'        => $model->slug,
            'status'      => $model->status,
            'description' => $model->description,
            'level'       => $model->level,
            'status'      => $model->status,
            'timestamps'  => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includePermissions($model)
    {
        return $this->collection($model->permissions, new PermissionTransformer());
    }

    public function includeUsers($model)
    {
        $user_transformer = config('user.transformers.user');
        return $this->collection($model->users, new $user_transformer());
    }
}
