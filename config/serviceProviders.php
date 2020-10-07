<?php

    declare(strict_types=1);


    return [
        'database' => [
            'class' => \App\ServiceProviders\FreakquentServiceProvider::class,
            'immediate' => true
        ],
        'request' => [
            'class' => \App\ServiceProviders\HttpRequestServiceProvider::class,
            'immediate' => false
        ],
        'session' => [
            'class' => \App\ServiceProviders\SessionServiceProvider::class,
            'immediate' => false
        ],
        'fileEngine' => [
            'class' => \App\ServiceProviders\FileSystemsProvider::class,
            'immediate' => false
        ]
    ];
