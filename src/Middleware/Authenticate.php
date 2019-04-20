<?php

namespace Larfree\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Larfree\Exceptions\UnauthorizedHttpException;

class Authenticate
{
    /**
     * @author iwulai
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth_user_id())
        {
            throw new UnauthorizedHttpException();
        }

        return $next($request);
    }
}
