<?php

namespace Multipedidos;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Model as BaseModel;
class AuthUserModel extends BaseModel
{
    use HasRoles;
    
    protected $guard_name = 'jwt';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->password = Hash::make($model->password);
            $model->uuid = (string) random_string_generator(30);
        });
    }

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