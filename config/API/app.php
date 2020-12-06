<?php

    declare(strict_types=1);

    // API Realm configuration


    return [
        'SERVICE_PROVIDERS' => [
            \App\ServiceProviders\DBServiceProvider::class,
            \App\ServiceProviders\HashingServiceProvider::class,
//            \App\ServiceProviders\SessionServiceProvider::class,
//            App\ServiceProviders\ViewServiceProvider::class,
            \App\ServiceProviders\FileSystemsProvider::class,
            \App\ServiceProviders\AuthServiceProvider::class
        ],

        'MIDDLEWARE' => [
//            App\Http\Middleware\StartSessionMiddleware::class,
//            App\Http\Middleware\AuthenticateSessionMiddleware::class,
            App\Http\Middleware\CheckApiToken::class,
            App\Http\Middleware\AuthenticateApi::class,
//            App\Http\Middleware\VerifyCSRFMiddleware::class,
//            App\Http\Middleware\SendCookiesMiddleware::class,
//            App\Http\Middleware\CreateFreshApiTokenMiddleware::class
        ]
    ];