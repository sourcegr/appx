<?php

    declare(strict_types=1);


    use App\Http\Auth\Middleware\AuthMiddleware;
    use App\Middleware\StartSessionMiddleware;

    use App\Middleware\CheckMaintenanceMode;
    use App\Middleware\RandomRouteMiddleware;

    return [
        'GLOBAL' => [
            'CheckMaintenanceMode' => CheckMaintenanceMode::class,
        ],

        'REALM' => [
            'WEB' => [
                'session' => StartSessionMiddleware::class,
                'auth' => AuthMiddleware::class,
            ],
            'API' => [

            ]
        ],

        'ROUTE' => [
            'MINE' => RandomRouteMiddleware::class,
            'GLOBAL_MINE' => RandomRouteMiddleware::class,
        ]
    ];
