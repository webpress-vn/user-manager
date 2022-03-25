<?php

namespace VCComponent\Laravel\User\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmailVerify
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->email_verified) {
            throw new UnauthorizedHttpException('', 'Email not verified');
        }

        return $next($request);
    }
}
