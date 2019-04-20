<?php

namespace Larfree\Exceptions;

use Exception;
use Throwable;
use Larfree\Support\ApiForm;
use Larfree\Support\ApiResponse;

class ApiException extends Exception
{
    protected $data = null;

    protected $message = null;

    protected $code = null;

    protected $status = null;

    public function __construct(string $message = null, int $status = null, $data = null, Throwable $previous = null)
    {
        $this->data = $data;

        $this->status = $status ?: $this->status ?: ApiResponse::HTTP_UNPROCESSABLE_ENTITY;

        if (! $message) $message = $this->message;

        parent::__construct($message, $this->status, $previous);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function render()
    {
        return ApiResponse::make(new ApiForm($this->data, $this->message, $this->status, $this->code));
    }
}