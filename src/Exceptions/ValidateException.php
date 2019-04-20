<?php

namespace Larfree\Exceptions;

use Throwable;
use Larfree\ApiResponse;
use Illuminate\Validation\Validator;

class ValidateException extends ApiException
{
    public function __construct(Validator $validator, Throwable $previous = null)
    {
        $errors = $validator->errors();

        parent::__construct($errors->first(), ApiResponse::HTTP_UNPROCESSABLE_ENTITY, $errors->messages(), $previous);
    }
}