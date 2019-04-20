<?php

namespace Larfree\Middleware;

use Closure;
use Illuminate\Http\Request;
use Larfree\Support\ApiForm;
use Larfree\Support\ApiResponse;
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

        if ($content instanceof ApiForm) return ApiResponse::make($content);

        if ($content instanceof AbstractPaginator) return ApiResponse::paginate($content->toArray());

        $form = new ApiForm(null, null, $status);

        if (is_string($content) || is_numeric($content))
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