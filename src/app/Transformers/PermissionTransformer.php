<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'                        => (int) $model->id,
            'name'                      => $model->name,
            'slug'                      => $model->slug,
            'description'               => $model->description,
            'model'                     => $model->model,
            'permission_group_id'       => (int) $model->permission_group_id,
            'timestamps'                => [
                'created_at'            => $model->created_at,
                'updated_at'            => $model->updated_at,
            ],
        ];
    }
}
