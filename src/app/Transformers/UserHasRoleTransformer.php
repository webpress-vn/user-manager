<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;

class UserHasRoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'         => (int) $model->id,
            'user_id'    => (int) $model->user_id,
            'role_id'    => (int) $model->role_id,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
