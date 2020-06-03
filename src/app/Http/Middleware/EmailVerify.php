<?php

namespace VCComponent\Laravel\User\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerify
{
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user->email_verified) {
            throw new UnauthorizedHttpException('', 'Email not verified');
        }

        return $next($request);
    }
}
