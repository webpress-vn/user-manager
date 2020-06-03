<?php

namespace VCComponent\Laravel\User\Traits;

use VCComponent\Laravel\User\Entities\UserMeta;

trait UserSchemaTrait
{
    public function userMetas()
    {
        return $this->hasMany(UserMeta::class);
    }

    public static function schema()
    {
        return [];
    }
}
