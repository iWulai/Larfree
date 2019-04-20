<?php

namespace Larfree;

use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class ApiForm
{
    protected $status;

    protected $code;

    protected $message;

    protected $data;

    protected $body = [];

    public function __construct($data = null, string $message = null, int $status = ApiResponse::HTTP_OK, int $code = null)
    {
        $this->status = $status;

        $this->code = $code ?? $status;

        $this->data = $data;

        $this->message = $message ?? ApiResponse::$statusTexts[$this->status];
    }

    public static function make($data = null, string $message = null, int $status = ApiResponse::HTTP_OK, int $code = null)
    {
        return new static($data, $message, $status, $code);
    }

    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function addData(string $key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function appendData($value)
    {
        $this->data[] = $value;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function addBody(string $key, $value)
    {
        $this->body[$key] = $value;

        return $this;
    }

    public function getBody()
    {
        return array_merge(['status' => $this->status, 'code' => $this->code, 'message' => $this->message, 'data' => $this->data], $this->body);
    }

    public function __toString()
    {
        return json_encode(array_map(function ($value)
            {
                if ($value instanceof JsonSerializable)
                {
                    return $value->jsonSerialize();
                }
                elseif ($value instanceof Jsonable)
                {
                    return json_decode($value->toJson(), true);
                }
                elseif ($value instanceof Arrayable)
                {
                    return $value->toArray();
                }

                return $value;
            },
            $this->getBody())
        );
    }
}