<?php

namespace Larfree\Exceptions;

use Larfree\Support\ApiResponse;

class UnauthenticatedException extends ApiException
{
    protected $message = '权限认证错误！请先登录。';

    protected $status = ApiResponse::HTTP_UNAUTHORIZED;
}
