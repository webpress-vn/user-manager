<?php

namespace VCComponent\Laravel\User\Traits;

use VCComponent\Laravel\User\Entities\User;

trait HasUserTrait
{
    public function user()
    {
        return $this->beLongsTo(User::class, 'author_id');
    }
}
