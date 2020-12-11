<?php
    declare(strict_types=1);

    return [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', ''),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),

        // Laravel style
        //        'client' => env('REDIS_CLIENT', 'phpredis'),
        //
        //        'options' => [
        //            'cluster' => env('REDIS_CLUSTER', 'redis'),
        //            'prefix' => env('REDIS_PREFIX', env('APP_NAME', 'Appx').'_redis'),
        //        ],
        //
        //        'default' => [
        //            'url' => env('REDIS_URL'),
        //            'host' => env('REDIS_HOST', '127.0.0.1'),
        //            'password' => env('REDIS_PASSWORD', null),
        //            'port' => env('REDIS_PORT', '6379'),
        //            'database' => env('REDIS_DB', '0'),
        //        ],
        //
        //        'cache' => [
        //            'url' => env('REDIS_URL'),
        //            'host' => env('REDIS_HOST', '127.0.0.1'),
        //            'password' => env('REDIS_PASSWORD', null),
        //            'port' => env('REDIS_PORT', '6379'),
        //            'database' => env('REDIS_CACHE_DB', '1'),
        //        ],
    ];