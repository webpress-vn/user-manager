<?php

namespace VCComponent\Laravel\User\Traits;

use Illuminate\Support\Facades\Validator;

trait UserValidatorTrait
{
    public function getSchemaRules($repository)
    {
        $schema = collect($repository->model()::schema());
        $rules  = $schema->map(function ($item) {
            return $item['rule'];
        });
        return $rules->toArray();
    }

    public function isSchemaValid($data, $rules)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new \Exception($validator->errors(), 1000);
        }
        return true;
    }

    public function getNoRuleFields($repository)
    {
        $schema = collect($repository->model()::schema());

        $fields = $schema->filter(function ($item) {
            return count($item['rule']) === 0;
        });

        return $fields->toArray();
    }
}
