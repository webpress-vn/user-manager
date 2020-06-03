<?php

namespace VCComponent\Laravel\User\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles',

    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }
    public function transform($model)
    {

        $transform = [
            'id'             => (int) $model->id,
            'email'          => $model->email,
            'username'       => $model->username,
            'first_name'     => $model->first_name,
            'last_name'      => $model->last_name,
            'phone_number'   => $model->phone_number,
            'address'        => $model->address,
            'gender'         => $model->gender,
            'birth'          => $model->birth,
            'last_login'     => $model->last_login,
            'avatar'         => $model->avatar ? $model->avatar : '',
            'status'         => (int) $model->status,
            'email_verified' => $model->email_verified,
        ];

        if ($model->userMetas->count()) {
            foreach ($model->userMetas as $item) {
                $transform[$item['key']] = $item['value'];
            }
        }

        $transform['timestamps'] = [
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];

        return $transform;
    }
    public function includeRoles($model)
    {
        return $this->collection($model->roles, new RoleTransformer());
    }
}
