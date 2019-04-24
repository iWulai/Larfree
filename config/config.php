<?php

return [

    'restful' => true,

    'auth' => [

        'user_id' => env('LARFREE_AUTH_USER_ID', null),

        'model' => Larfree\Auth\UserAuth::class,

        'login_column' => 'cellphone',

        'password_regex' => '/^[a-zA-Z0-9]{3,18}$/',

        'validator_messages' => [

            'phone' => [

//                'username.required' => '',
//
//                'username.cellphone' => '',
//
//                'password.required' => '',
//
//                'password.password' => '',

            ],

            'email' => [

//                'username.required' => '',
//
//                'username.email' => '',
//
//                'password.required' => '',
//
//                'password.password' => '',

            ],

        ],

    ],

    'route' => [

        'prefix' => null,

    ],

];
