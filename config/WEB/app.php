<?php

    declare(strict_types=1);

    // WEB Realm configuration


    return [
        'SERVICE_PROVIDERS' => [
            \App\ServiceProviders\DBServiceProvider::class,
            \App\ServiceProviders\HashingServiceProvider::class,
            \App\ServiceProviders\SessionServiceProvider::class,
            App\ServiceProviders\ViewServiceProvider::class,
            \App\ServiceProviders\FileSystemsProvider::class,
            \App\ServiceProviders\AuthServiceProvider::class
        ],

        'MIDDLEWARE' => [
            App\Http\Middleware\StartSessionMiddleware::class,
            App\Http\Middleware\TestMiddleware::class,
            App\Http\Middleware\VerifyCSRFMiddleware::class,
            App\Http\Middleware\SendCookiesMiddleware::class,
            App\Http\Middleware\CreateFreshApiTokenMiddleware::class
        ]
    ];