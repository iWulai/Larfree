<?php

namespace Larfree\Exceptions;

use Larfree\Support\ApiResponse;

class UnauthorizedHttpException extends ApiException
{
    protected $message = '用户认证错误！未登录。';

    protected $status = ApiResponse::HTTP_UNAUTHORIZED;

    protected $code = 40100;
}
