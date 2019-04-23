<?php

namespace Larfree\Middleware;

use Closure;
use Larfree\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Larfree\Exceptions\AuthenticationExpired;
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
     * @throws AuthenticationExpired
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth_user_id())
        {
            if (! Auth::check())
            {
                throw new AuthenticationExpired();
            }

            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
