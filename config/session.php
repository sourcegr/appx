<?php

    declare(strict_types=1);

    use App\App;

    return [
        'DRIVER' => env('SESSION_DRIVER', 'DB'),
        'LIFETIME' => env('SESSION_LIFETIME', 30 * 24 * 60), // 1 month
        'ENCRYPT' => true,
        'COOKIE' => env('SESSION_COOKIE', 'appx_session'),
        'PATH' => env('SESSION_PATH', '/'),
        'DOMAIN' => env('SESSION_DOMAIN', null),
        'SECURE' => env('SESSION_SECURE_COOKIE', false),
        'HTTP_ONLY' => env('SESSION_HTTP_ONLY', false),

        'TOKEN_NAME' => null, // the key representing the token
        'USER_ID_FIELD' => null, // the key to determine users


        'DRIVERS' => [
            'file' => [
                'path' => App::APP_STORAGE_PATH . 'sessions/'
            ],

            'DB' => [
                'connection' => 'default',
                'table' => 'internal_sessions'
            ],

        ]
    ];
