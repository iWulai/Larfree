<?php

return [

    'restful' => true,

    'auth' => [

        'user_id' => env('LARFREE_AUTH_USER_ID', null),

        'model' => Larfree\Auth\UserAuth::class,

    ],

    'route' => [

        'prefix' => 'api',

    ],

];
