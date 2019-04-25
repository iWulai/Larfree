<?php

use Illuminate\Support\Facades\{Route, Config};

$route = Route::middleware('api');

if ($prefix = Config::get('larfree.route.prefix')) $route->prefix($prefix);

$route->group(function ()
    {
        Route::namespace('Larfree')->prefix('larfree')->group(function ()
            {
                Route::namespace('Auth')->prefix('auth')->group(function ()
                    {
                        Route::post('/login/phone', 'AuthController@loginUsePhone');

                        Route::post('/login/email', 'AuthController@loginUseEmail');

                            Route::middleware('larfree.auth')->group(function ()
                            {
                                Route::get('/logout', 'AuthController@logout');
                            }
                        );
                    }
                );
            }
        );
    }
);