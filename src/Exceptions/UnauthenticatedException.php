<?php

namespace Larfree\Exceptions;

use Larfree\Support\ApiResponse;

class UnauthenticatedException extends ApiException
{
    protected $code = ApiResponse::HTTP_UNAUTHORIZED;

    protected $message = '权限认证错误！请先登录。';
}
