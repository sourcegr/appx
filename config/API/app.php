<?php

    declare(strict_types=1);

    // API Realm configuration

    use App\Http\Middleware\AuthenticateApi;
    use App\Http\Middleware\CheckApiToken;
    use App\ServiceProviders\AuthServiceProvider as AuthServiceProvider;
    use App\ServiceProviders\DBServiceProvider as DBServiceProvider;
    use App\ServiceProviders\FileSystemsProvider as FileSystemsProvider;
    use App\ServiceProviders\HashingServiceProvider as HashingServiceProvider;
    use App\ServiceProviders\RedisServiceProvider;


    return [
        'SERVICE_PROVIDERS' => [
            DBServiceProvider::class,
            HashingServiceProvider::class,
            FileSystemsProvider::class,
            AuthServiceProvider::class,
            RedisServiceProvider::class
        ],

        'MIDDLEWARE' => [
            CheckApiToken::class,
            AuthenticateApi::class,
        ]
    ];