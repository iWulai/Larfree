<?php

namespace Larfree\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Larfree\Support\ApiResponse;
use Larfree\Support\ApiResource;
use Illuminate\Pagination\AbstractPaginator;

class FormatResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $request->headers->set('Accept', 'application/json');
        /**
         * @var \Illuminate\Http\Response $response
         */
        $response = $next($request);

        if ($response instanceof ApiResponse) return $response;

        $status = $response->getStatusCode();

        if ($status >= ApiResponse::HTTP_INTERNAL_SERVER_ERROR) return $response;

        $content = $response->getOriginalContent();

        if ($content instanceof ApiResource) return ApiResponse::make($content);

        $form = new ApiResource(null, null, $status);

        if ($content instanceof AbstractPaginator)
        {
            $content = $content->toArray();

            $form->setData($content['data']);

            $form->addBody('link', Arr::only($content, ['first_page_url', 'last_page_url', 'prev_page_url', 'next_page_url']));

            $form->addBody('meta', Arr::only($content, ['current_page', 'last_page', 'per_page', 'total']));
        }
        elseif (is_string($content) || is_numeric($content))
        {
            $form->setMessage($content);
        }
        else
        {
            $form->setData($content);
        }

        return ApiResponse::make($form);
    }
}