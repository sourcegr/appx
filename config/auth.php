<?php

    declare(strict_types=1);

    use App\Http\Auth\Drivers\SessionAuthDriver;
    use App\Http\Auth\Providers\FreakquentUserProvider;
    use App\Models\User;

    return [

        'REALMS' => [
            'WEB' => [
                'DRIVER' => SessionAuthDriver::class,
                'USER_PROVIDER' => [
                    'CLASS' => FreakquentUserProvider::class,
                    'CONFIG' => [
                        'MODEL' => User::class
                    ]
                ]
            ],
            'API' => [
            ]
        ]
    ];