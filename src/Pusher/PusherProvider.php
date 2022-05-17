<?php

namespace Multipedidos\Pusher;

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
        $this->app->bind('Pusher', function () {
            return new \Multipedidos\Pusher();   
        });

        class_alias(\Multipedidos\Pusher\Facade::class, 'Pusher');
    }
}
