<?php

use Illuminate\Support\Facades\{Auth, Config};

if (! function_exists('auth_user_id'))
{
    function auth_user_id(int $userId = null)
    {
        if (is_null($userId))
        {
            $userId = Auth::id() ?: Config::get('larfree.auth.user_id', null);
        }
        else
        {
            Config::set('larfree.auth.user_id', $userId);
        }

        return intval($userId);
    }
}