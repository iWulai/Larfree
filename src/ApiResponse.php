<?php

namespace Larfree;

use Illuminate\Support\Arr;
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

    public static function make(ApiForm $form)
    {
        return new static($form->getBody(), Config::get('larfree.restful', true) === true ? $form->getStatus() : static::HTTP_OK, static::HEADERS);
    }

    public static function paginate(array $content, array $appends = null)
    {
        $resource = new ApiForm(Arr::get($content, 'data', []));

        $resource->addBody('link', Arr::only($content, ['first_page_url', 'last_page_url', 'prev_page_url', 'next_page_url']));

        $resource->addBody('meta', Arr::only($content, ['current_page', 'last_page', 'per_page', 'total']));

        if ($appends)
        {
            foreach ($appends as $append => $default)
            {
                $resource->addBody($append, Arr::get($content, $append, $default));
            }
        }

        return static::make($resource);
    }
}