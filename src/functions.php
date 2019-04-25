<?php

use Larfree\Auth\AuthRepository;
use Larfree\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\{Auth, Config};

if (! function_exists('auth_user_id'))
{
    /**
     * @author iwulai
     *
     * @param int|null $userId
     *
     * @return int
     *
     * @throws \Larfree\Exceptions\ApiErrorException
     * @throws \Exception
     */
    function auth_user_id(int $userId = null)
    {
        if (is_null($userId))
        {
            $userId = Auth::guard()->id() ?: Config::get('larfree.auth.user_id', null);
        }
        else
        {
            if ($user = (new AuthRepository())->find($userId))
            {
                Auth::guard()->login($user);
            }
            else
            {
                throw new UnauthorizedException('认证异常！未注册。', 40100);
            }
        }

        return intval($userId);
    }
}