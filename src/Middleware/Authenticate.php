<?php

namespace Larfree\Middleware;

use Closure;
use Larfree\ApiResponse;
use Illuminate\Http\Request;
use Larfree\Exceptions\UnauthorizedException;

class Authenticate
{
    /**
     * @author iwulai
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return ApiResponse
     *
     * @throws UnauthorizedException
     * @throws \Larfree\Exceptions\ApiErrorException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $userId = auth_user_id())
        {
            throw new UnauthorizedException();
        }

        auth_user_id($userId);

        return $next($request);
    }
}
