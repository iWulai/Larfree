<?php

namespace Larfree\Middleware;

use Closure;
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
     * @return \Larfree\ApiResponse
     *
     * @throws AuthenticationExpired
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth_user_id())
        {
            throw new UnauthorizedException();
        }

        if (! Auth::check())
        {
            throw new AuthenticationExpired();
        }


        return $next($request);
    }
}
