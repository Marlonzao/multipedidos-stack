<?php

namespace Multipedidos\Middleware;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
class Provider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->middleware([
            CORSMiddleware::class
        ]);

        app()->routeMiddleware([
            'auth' => AuthenticationMiddleware::class,
            'authorization'  => AuthorizationMiddleware::class,
        ]);
        
    }
}
