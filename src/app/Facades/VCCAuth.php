<?php

namespace VCComponent\Laravel\User\Facades;

use Illuminate\Support\Facades\Facade;

class VCCAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vcc.auth';
    }
}
