<?php

use Illuminate\Support\Facades\{Auth, Config};

if (! function_exists('auth_user_id'))
{
    function auth_user_id(int $userId = null)
    {
        if (is_null($userId))
        {
            if (! $id = Auth::id())
            {
                if (Config::get('app.debug', false))
                {
                    $id = Config::get('larfree.auth.user_id', null);
                }
            }

            $userId = $id;
        }
        else
        {
            Config::set('larfree.auth.user_id', $userId);
        }

        return intval($userId);
    }
}