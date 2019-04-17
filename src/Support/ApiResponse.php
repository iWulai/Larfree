<?php

namespace Larfree\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class ApiResponse extends JsonResponse
{
    protected const HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Origin, Content-Type, Authorization, Cookie, Accept',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
        'Access-Control-Allow-Credentials' => 'false',
    ];

    public static function make(ApiResource $form)
    {
        return new static($form->getBody(), Config::get('larfree.restful', true) === true ? $form->getStatus() : static::HTTP_OK, static::HEADERS);
    }
}