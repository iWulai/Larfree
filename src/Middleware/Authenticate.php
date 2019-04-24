<?php

namespace Larfree\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Larfree\Exceptions\AuthExpiredException;
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
     *
     * @throws AuthExpiredException
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth_user_id())
        {
            if (($app = App::getFacadeApplication()) && $app->has('tymon.jwt.auth'))
            {
                /**
                 * @var \Tymon\JWTAuth\JWT $auth
                 */
                $auth = $app->get('tymon.jwt.auth');

                try
                {
                    $auth->parseToken()->invalidate();
                }
                catch (\Exception $exception)
                {
                    throw new AuthExpiredException();
                }
            }

            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
