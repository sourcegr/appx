<?php

    declare(strict_types=1);


    return [
        'database' => [
            'class' => \App\ServiceProviders\FreakquentServiceProvider::class,
            'immediate' => true
        ],
        'request' => [
            'class' => \App\ServiceProviders\HttpRequestServiceProvider::class,
        ],
        'session' => [
            'class' => \App\ServiceProviders\SessionServiceProvider::class,
        ],
        'fileEngine' => [
            'class' => \App\ServiceProviders\FileSystemsProvider::class,
        ],
        'realm' => [
            'class' => \App\ServiceProviders\RealmProvider::class,
        ],
        'router' => [
            'class' => \App\ServiceProviders\RouterProvider::class,
        ],
        'auth' => [
            'class' => \App\ServiceProviders\AuthServiceProvider::class
        ]
    ];
