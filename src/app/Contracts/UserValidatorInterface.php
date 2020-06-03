<?php

namespace VCComponent\Laravel\User\Contracts;

interface UserValidatorInterface
{
    public function getSchemaRules($repository);
    public function isSchemaValid($data, $rules);
    public function getNoRuleFields($repository);
}
