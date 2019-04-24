<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Larfree')->prefix('larfree')->group(function ()
    {
        Route::namespace('Auth')->prefix('auth')->group(function ()
            {
                Route::post('/login/phone', 'AuthController@loginUsePhone');

                Route::post('/login/email', 'AuthController@loginUseEmail');

                Route::middleware('larfree.auth')->group(function ()
                    {
                        Route::post('/logout', 'AuthController@logout');
                    }
                );
            }
        );
    }
);