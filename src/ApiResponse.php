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

    public static function paginate(array $paginatorContent, array $appends = null)
    {
        $resource = new ApiForm(Arr::get($paginatorContent, 'data', []));

        $resource->addBody('link', Arr::only($paginatorContent, ['first_page_url', 'last_page_url', 'prev_page_url', 'next_page_url']));

        $resource->addBody('meta', Arr::only($paginatorContent, ['current_page', 'last_page', 'per_page', 'total']));

        if ($appends)
        {
            foreach ($appends as $key => $value)
            {
                $resource->addBody($key, $value);
            }
        }

        return static::make($resource);
    }
}