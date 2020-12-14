<?php

    declare(strict_types=1);


    // WEB Realm configuration

    use App\ServiceProviders\MessagingServiceProvider;
    use App\ServiceProviders\RedisServiceProvider;
    use Sourcegr\Framework\Messaging\RedisMessage;


    return [
        'SERVICE_PROVIDERS' => [
            App\ServiceProviders\DBServiceProvider::class,
            App\ServiceProviders\HashingServiceProvider::class,
            App\ServiceProviders\SessionServiceProvider::class,
            App\ServiceProviders\ViewServiceProvider::class,
            App\ServiceProviders\FileSystemsProvider::class,
            App\ServiceProviders\AuthServiceProvider::class,
            RedisServiceProvider::class,
            MessagingServiceProvider::class

        ],

        'MIDDLEWARE' => [
            App\Http\Middleware\StartSessionMiddleware::class,
            App\Http\Middleware\AuthenticateSessionMiddleware::class,
            App\Http\Middleware\TestMiddleware::class,
            App\Http\Middleware\VerifyCSRFMiddleware::class,
            App\Http\Middleware\SendCookiesMiddleware::class,
            App\Http\Middleware\CreateFreshApiTokenMiddleware::class,
        ],
    ];