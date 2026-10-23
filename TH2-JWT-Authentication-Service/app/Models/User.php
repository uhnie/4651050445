<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'users';
    protected $primaryKey = 'IdUser';
    public $timestamps = false;

    protected $fillable = [
        'UserName',
        'Password',
        'Token',
    ];

    protected $hidden = [
        'Password',
        'Token',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'username' => $this->UserName,
        ];
    }
}
