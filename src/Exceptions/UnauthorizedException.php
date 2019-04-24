<?php

namespace Larfree\Exceptions;

use Larfree\ApiResponse;

class UnauthorizedException extends ApiException
{
    protected $message = '认证异常！未登录。';

    protected $status = ApiResponse::HTTP_UNAUTHORIZED;

    protected $code = 40100;
}
