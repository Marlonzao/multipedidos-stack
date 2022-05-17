<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

use Firebase\JWT\Key;
use Firebase\JWT\JWT as FirebaseJWT;
use Spatie\Permission\Models\Role;

class AuthProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Auth::viaRequest('jwt', function (Request $request) {

            $bearerToken = $request->bearerToken();

            if(empty($bearerToken))
                throw new \Multipedidos\Exception\TokenNotProvided();
            
            try {
                $credentials = \Firebase\JWT\JWT::decode($bearerToken, new Key(env('JWT_SECRET'), env('JWT_ALG', 'HS512')));
            } catch(\Firebase\JWT\ExpiredException $e) {
                throw new \Multipedidos\Exception\TokenExpired();
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                throw new \Multipedidos\Exception\BadToken();
            }

            $modelQuery = config("userType.{$credentials->type}.model");
            $user = $modelQuery::findByUUID($credentials->sub);

            if (!$user)
                throw new \Multipedidos\Exception\UserNotFound();

            $user->type = $credentials->type;

            return $user;
        });
        
        Auth::viaRequest('login', function (Request $request) {
            $modelQuery = config("userType.{$request->userType}.model");

            $user = $modelQuery::findByEmail($request->email);

            if (!$user)
                throw new \Multipedidos\Exception\UserNotFound();
                
            if (!Hash::check($request->password, $user->password))
                throw new \Multipedidos\Exception\InvalidCredentials();
            
            $user->type = $request->userType;

            return $user;
        });

        Auth::guard('jwt')->macro('userType', function () {
            return Auth::user()->type;
        });

        Auth::guard('jwt')->macro('permissions', function () {
            $roleID = Auth::user()->role_id;

            $rolePermissions = Role::findById($roleID)->permissions;
            $userPermissions = Auth::user()->permissions;

            return $rolePermissions->merge($userPermissions)->pluck('name');
            
        });

        Auth::guard('jwt')->macro('merchant', function () {
            return Auth::user()->merchant;
        });

        Auth::guard('jwt')->macro('userResource', function () {
            $user = Auth::user();
            $resource = config("userType.{$user->type}.resource");

            return new $resource($user);
        });
    }
}