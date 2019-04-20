<?php

namespace Larfree;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as Authenticate;

class AuthModel extends Model implements Authenticate
{
    use Authenticatable;
}
