<?php

namespace Larfree\Exceptions;

use Larfree\ApiResponse;

class AuthExpiredException extends ApiException
{
    protected $message = '认证异常！已失效。';

    protected $status = ApiResponse::HTTP_UNAUTHORIZED;

    protected $code = 40104;
}
