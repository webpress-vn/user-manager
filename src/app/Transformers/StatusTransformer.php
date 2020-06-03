<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\User\Entities\Status;

class StatusTransformer extends TransformerAbstract
{
    public function transform(Status $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
