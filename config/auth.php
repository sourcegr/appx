<?php

    declare(strict_types=1);


    return [

        'REALM' => [
            'API' => [
                'token' => [
                    'driver' => 'token',
                    'provider' => 'users',

                    'driver_params' => [ // optional, if you want to allow get/post lookup for the token
                        // otherwise only searches for the Authorization: Bearer XXXXXX
                        'allow_GET' => false,
                        'allow_POST' => false,
                        'token_field' => 'token', // optional. Will get the token_field fromm the provider
                    ]
                ]
            ],
            'WEB' => [
                'session' => [
                    'driver' => 'session',
                    'provider' => 'users'
                ],

            ]
        ],

        'providers' => [
            'users' => [
                'engine' => 'DB',
                'connection' => 'default', // can be omitted
                'hasher' => 'default', // can be omitted

                'table' => 'internal_users',
                'id_field' => 'id',
                'token_field' => 'token',
                'login_field' => 'email',
                'password_field' => 'password',
            ],
        ],

        'default' => [
            'API' => 'token',
            'WEB' => 'session',
        ]


    ];