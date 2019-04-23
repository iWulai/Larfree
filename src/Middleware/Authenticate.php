<?php

namespace Larfree\Middleware;

use Closure;
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
     * @return mixed
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth_user_id())
        {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
