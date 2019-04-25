<?php

return [

    'restful' => true,

    'auth' => [

        'user_id' => env('LARFREE_AUTH_USER_ID', null),

        'model' => Larfree\Auth\JWTAuthModel::class,

    ],

    'route' => [

        'prefix' => 'api',

    ],

];
