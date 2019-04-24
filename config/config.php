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

                'username.required' => '手机号码必填！',

                'username.cellphone' => '手机号码格式错误！',

                'password.required' => '密码必填！',

                'password.password' => '密码只允许数字或字母！',

            ],

            'email' => [

                'username.required' => '邮箱地址格式错误！',

                'username.email' => '邮箱地址格式错误！',

                'password.required' => '密码必填！',

                'password.password' => '密码只允许数字或字母！',

            ],

        ],

    ],

    'route' => [

        'prefix' => 'api',

    ],

];
