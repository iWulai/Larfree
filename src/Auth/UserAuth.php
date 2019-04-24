<?php

namespace Larfree\Auth;

use Larfree\AuthModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserAuth extends AuthModel implements JWTSubject
{
    protected $table = 'users';

    protected $loginColumn = 'cellphone';

    public function setLoginColumn(string $column)
    {
        $this->loginColumn = $column;

        return $this;
    }

    public function getLoginColumn()
    {
        return $this->loginColumn;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}