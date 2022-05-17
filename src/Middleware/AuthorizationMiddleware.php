<?php

namespace Multipedidos\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorizationMiddleware
{
    private $request;

    public function handle($request, Closure $next, ...$permissions)
    {
        $userPermissions = Auth::permissions();

        if ($userPermissions->intersect($permissions)->isEmpty()) 
            throw new \Multipedidos\Exception\InsuficientPermissions();

        return $next($request);
    }
}