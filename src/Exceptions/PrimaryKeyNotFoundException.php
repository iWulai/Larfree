<?php

namespace Larfree\Exceptions;

use Exception;

class PrimaryKeyNotFoundException extends Exception
{
    protected $message = 'Not found the primary key!';
}