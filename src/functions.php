<?php

use Larfree\Auth\AuthRepository;
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
     */
    function auth_user_id(int $userId = null)
    {
        if (is_null($userId))
        {
            $userId = Auth::id() ?: Config::get('larfree.auth.user_id', null);
        }
        else
        {
            if ($user = (new AuthRepository())->findUser($userId))
            {
                Auth::guard()->login($user);
            }
        }

        return intval($userId);
    }
}