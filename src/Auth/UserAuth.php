<?php

namespace Larfree\Auth;

use Larfree\AuthModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserAuth extends AuthModel implements JWTSubject
{
    protected $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}