<?php

namespace Larfree;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as Authenticate;

abstract class AuthModel extends Model implements Authenticate
{
    use Authenticatable;
}
