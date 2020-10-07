<?php

    declare(strict_types=1);

    use App\App;

    return [
        'driver' => env('SESSION_DRIVER', 'native'),
        'lifetime' => env('SESSION_LIFETIME', 120), // minutes
        'autostart' => env('SESSION_AUTOSTART',true),
        'encrypt' => false,


        'cookie' => env('SESSION_COOKIE', 'appx_session'),
        'path' => env('SESSION_PATH', '/'),
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => env('SESSION_HTTP_ONLY', false),



        'drivers' => [
            'native' => [
                'class' => \Sourcegr\Framework\Http\Session\Drivers\NativeSessionDriver::class,
            ],
            'file' => [
                // 'class' => \Sourcegr\Http\Session\Drivers\FileSessionDriver::class,
                'path' => App::APP_STORAGE_PATH . 'sessions/'
            ],

            'DB' => [
//                'class' => \Sourcegr\Http\Session\Drivers\DBSessionDriver::class,
                'table' => env('SESSION_DB_TABLE', 'appx_sessions'),
                'connection' => env('SESSION_DB_DRIVER', 'mysql'),
            ],

            'custom' => [
                'class' => '\\Namespaced\\Class\\SessionDriver',
                'params' => [
                    'param1' => 1,
                    'param2' => 2,
                ]
            ]
        ]
    ];
