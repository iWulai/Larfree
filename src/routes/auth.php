<?php

use Illuminate\Support\Facades\Route;

Route::prefix('larfree')->group(function ()
    {
        Route::namespace('Auth')->prefix('auth')->group(function ()
            {
                Route::post('/login/phone', 'AuthController@loginUsePhone');

                Route::post('/login/email', '\\Larfree\\Auth\\AuthController@loginUseEmail');
            }
        );

        Route::namespace('Auth')->prefix('auth')->middleware('larfree.auth')->group(function ()
            {
                Route::post('/logout', '\\Larfree\\Auth\\AuthController@logiout');
            }
        );
    }
);