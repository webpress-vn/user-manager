<?php

namespace VCComponent\Laravel\User\Repositories;

use Illuminate\Support\Facades\Cache;
use Prettus\Repository\Helpers\CacheKeys;

trait CanFlushCache
{
    public function flushCache()
    {
        $keys = CacheKeys::getKeys(get_called_class());
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
