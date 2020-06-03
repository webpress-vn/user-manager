<?php

namespace VCComponent\Laravel\User\Contracts;

interface UserSchema
{
    public function userMetas();

    public static function schema();
}
