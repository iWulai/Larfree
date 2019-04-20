<?php

namespace Larfree\Exceptions;

use Larfree\ApiResponse;

class AuthorizedTimeOverException extends ApiException
{
    protected $message = '用户认证错误！已过期。';

    protected $status = ApiResponse::HTTP_UNAUTHORIZED;

    protected $code = 40101;
}
