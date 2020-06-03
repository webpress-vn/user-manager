<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

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
            'status'      =>$model->status,
            'timestamps'  => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
