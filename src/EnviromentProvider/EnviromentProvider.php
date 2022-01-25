<?php 
    namespace Multipedidos;

    use Illuminate\Support\ServiceProvider;
    class MockeryServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $mocks = config('mocks');

            foreach([
                'bind',
                'scoped',
                'singleton',
            ] as $bind) {

                $mockerys = $mocks[$bind];

                foreach ($mockerys as $facade => $mockery) {
                    $this->app->{$bind}($facade, function($app) use ($mockery) {
                        return new $mockery[env('APP_ENV')];
                    });
                }

            }
        }
    }
