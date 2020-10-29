<?php

    declare(strict_types=1);

    return [
        'default' => 'mysql',

        'providers' => [
            'mysql' => [
                'active' => true,
                'engine' => env('DB_DRIVER', 'mysql'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'user' => env('DB_USER', 'root'),
                'password' => env('DB_PASS', 'root'),
                'port' => env('DB_PORT', 3306),
                'encoding' => env('DB_ENCODING', 'UTF8'),
                'db' => env('DB_DATABASE', 'test'),
                'pdo_params' => [
                    PDO::MYSQL_ATTR_FOUND_ROWS   => true,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            ],
            'mysql1' => [
                // ....
            ],
            'postgress' => [
                // ....
            ]
        ]
    ];