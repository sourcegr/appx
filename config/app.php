<?php

    declare(strict_types=1);

    // GLOBAL configuration
    return [
        'name' => env('APP_NAME', 'AppxApp'),
        'app_key' => env('APP_KEY', 'afansdbadflaweckjdxcdvnwiwrednmcx23'),
        'encryption_cipher' => 'AES-256-CBC',
        'encrypt_cookies' => true,
        'locale' => 'en',
        'timezone' => 'Europe/Athens',
        'env' => env('APP_ENV', 'production'),

        // List of service providers that need to be registered
        'SERVICE_PROVIDERS' => [
            \App\ServiceProviders\RouteServiceProvider::class,
//            \App\ServiceProviders\LoggingServiceProvider::class
        ],


        // list of global middleware that need to be registered
        'MIDDLEWARE' => [
            App\Http\Middleware\CheckMaintenanceModeMiddleware::class
        ],

        // CORS Configuration
        'CORS' => [
            'Access-Control-Expose-Headers' => 'ETag, Link, Location, Retry-After',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type, Accept-Encoding, X-Requested-With, User-Agent, ETag, Link, Location, Retry-After',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE',
        ]
    ];