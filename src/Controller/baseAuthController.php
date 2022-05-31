<?php

namespace Multipedidos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{

    protected $userType;
    private   $authGuard, $userID, $payload;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->request->userType = $this->userType;

        $this->authGuard = Auth::guard('login');
    }
    
    public function authenticate()
    {
        $this->validateEmail($this->request->email);
        $this->validatePassword($this->request->password);

        $this->auth();
        $this->payload();

        return [
            'token' => $this->payload,
            'user'  => new (config("userType.{$this->userType}.resource"))($this->authGuard->user())
        ];
    }

    public function refreshToken()
    {
        $token = $this->request->token;
        if (!$token)
            throw new \Multipedidos\Exception\TokenNotProvided();
        
        try {
            FirebaseJWT::decode($token, new Key(env('JWT_SECRET'), env('JWT_ALG', 'HS512')));

            return [
                'token'   => $token,
                'message' => 'Token is fine'
            ];
        } catch (\Firebase\JWT\ExpiredException $e) {
            try {
                $newToken = $this->refreshJWTToken($token);

                return [
                    'token'   => $newToken,
                    'message' => 'A new token was generated'
                ];

            } catch (\Firebase\JWT\ExpiredException $e) {
                throw new \Multipedidos\Exception\TokenTooOld();
            }
        } catch (\Exception $e) {
            throw new \Multipedidos\Exception\BadToken();
        }
    }

    private function refreshJWTToken($token)
    {
        $payload = explode(".", $token)[1];
        $decoded = json_decode(base64_decode($payload));

        if ($decoded->iat <= strtotime('-14 days'))
            throw new \Firebase\JWT\ExpiredException();

        $this->userUUID = $decoded->sub;
        return $this->payload();
    }

    private function auth()
    {
        $this->authGuard->validate(['request' => $this->request]);
        $this->userUUID = $this->authGuard->user()->uuid;
    }

    private function payload()
    {
        $payload = [
            'type' => $this->userType,
            'iat'  => time(),
            'iss'  => env('APP_NAME'),
            'exp'  => time() + 60 * 60 * 2,
            'sub'  => $this->userUUID
        ];

        $this->payload = FirebaseJWT::encode($payload, env('JWT_SECRET'), env('JWT_ALG', 'HS512'));
        return $this->payload;
    }

    private function validateEmail($email)
    {
        if(!isset($email) || empty($email) || is_null($email))
            throw new Multipedidos\Exception\InvalidField('Email is required');

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Multipedidos\Exception\InvalidField('Invalid email');
    }

    private function validatePassword($password)
    {
        if(!isset($password) || empty($password) || is_null($password))
            throw new Multipedidos\Exception\InvalidField('password is required');
    }

    public function getUser() 
    {
        return Auth::userResource();
    }
}
