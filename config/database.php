<?php

    declare(strict_types=1);

    return [
        'default' => 'postgres',
//        'default' => 'mysql',


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
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                ]
            ],
            'postgres' => [
                'active' => true,
                'engine' => 'pgsql',
                'host' => '127.0.0.1',
                'user' => 'default',
                'password' => 'secret',
                'port' => 5432,
                'encoding' => env('DB_ENCODING', 'UTF8'),
                'db' => 'test',
                'pdo_params' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            ],
        ]
    ];