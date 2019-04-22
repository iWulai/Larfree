<?php

namespace Larfree\Exceptions;

use Exception;
use Larfree\ApiForm;
use Larfree\ApiResponse;

class ApiException extends Exception
{
    protected $data = null;

    protected $message = null;

    protected $code = null;

    protected $status = null;

    public function __construct(string $message = null, int $status = null, $data = null, int $code = null)
    {
        $this->data = $data ?: $this->data;

        $this->status = $status ?: $this->status ?: ApiResponse::HTTP_UNPROCESSABLE_ENTITY;

        $this->message = $message ?: $this->message ?: null;

        $this->code = $code ?: $this->code;

        if (false) parent::__construct();
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
        return ApiResponse::make(new ApiForm($this->data, $this->status, $this->message, $this->code));
    }
}