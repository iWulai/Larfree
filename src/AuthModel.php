<?php

namespace Larfree;

use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as Authenticate;

abstract class AuthModel extends Model implements Authenticate, JWTSubject
{
    use Authenticatable;

    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
