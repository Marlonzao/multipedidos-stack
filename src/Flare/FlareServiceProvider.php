<?php

namespace Multipedidos\Flare;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use \Multipedidos\Exception\BadToken;
use \Multipedidos\Exception\UserNotFound;
use \Multipedidos\Exception\TokenExpired;
use \Multipedidos\Exception\InvalidUserType;
use \Multipedidos\Exception\TokenNotProvided;
use \Multipedidos\Exception\InvalidCredentials;
use \Multipedidos\Exception\InsuficientPermissions;
use \Multipedidos\Exception\TokenTooOld;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class Provider extends BaseServiceProvider
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    const DONT_REPORT = [
        HttpException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        AuthorizationException::class,
        ModelNotFoundException::class,

        BadToken::class,
        TokenTooOld::class,
        InvalidField::class,
        UserNotFound::class,
        TokenExpired::class,
        InvalidUserType::class,
        TokenNotProvided::class,
        InvalidCredentials::class,
        InsuficientPermissions::class,
    ];

    public function boot()
    {
        \Flare::filterExceptionsUsing(function (Throwable $throwable){
            foreach (self::DONT_REPORT as $class) {
                if ($throwable instanceof $class) {
                    return false;
                }
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Flare', function () {
            return app(\Multipedidos\Flare\Manager::class)->driver(env('APP_ENV'));   
        });

        if(!class_exists('\Flare'))
            class_alias(\Multipedidos\Flare\Facade::class, 'Flare');
    }
}
