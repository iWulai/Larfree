<?php

namespace Larfree\Exceptions;

use Larfree\ApiResponse;

class ApiErrorException extends ApiException
{
    protected $status = ApiResponse::HTTP_UNPROCESSABLE_ENTITY;
}