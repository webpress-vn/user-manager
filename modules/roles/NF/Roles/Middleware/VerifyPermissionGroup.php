<?php

namespace NF\Roles\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use NF\Roles\Exceptions\PermissionGroupDeniedException;

class VerifyPermissionGroup
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $permission
     * @return mixed
     * @throws \NF\Roles\Exceptions\PermissionGroupDeniedException
     */
    public function handle($request, Closure $next, $permission_group)
    {
        if ($this->auth->check() && $this->auth->user()->can($permission_group)) {
            return $next($request);
        }

        throw new PermissionGroupDeniedException($permission_group);
    }
}
