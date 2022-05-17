<?php

namespace Multipedidos\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticationMiddleware
{
    private $request;

    public function handle($request, Closure $next, ...$mustBe)
    {
        $this->request = $request;

        Auth::guard('jwt')->validate(['request' => $this->request]);

        if(sizeof($mustBe) > 0 && !in_array(Auth::user()->type, $mustBe))
            throw new \Multipedidos\Exception\InvalidUserType();

        return $next($this->request);
    }
}