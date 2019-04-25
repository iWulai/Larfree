<?php

namespace Larfree;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as Authenticate;

abstract class AuthModel extends Model implements Authenticate
{
    use Authenticatable;

    protected $hidden = ['id', 'password'];
}
