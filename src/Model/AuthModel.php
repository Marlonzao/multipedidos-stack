<?php

namespace Multipedidos;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Spatie\Permission\Traits\HasRoles;

class AuthUserModel extends BaseModel
{
    use HasRoles;
    
    protected $guard_name = 'jwt';

    public function scopefindByUUID($query, $uuid)
    {
        return $query->where([
            ['uuid', '=', $uuid]
        ])->first();
    }

    public function scopefindByEmail($query, $email)
    {
        return $query->where([
            ['email', '=', $email]
        ])->first();
    }
}