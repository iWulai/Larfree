<?php

return [

    'restful' => true,

    'auth' => [

        'user_id' => env('LARFREE_AUTH_USER_ID', null),

        'model' => env('LARFREE_AUTH_MODEL', '\Larfree\Auth\JWTAuthModel'),

    ],

    'route' => [

        'prefix' => 'api',

    ],

];
