<?php

    declare(strict_types=1);

    return [
        'mysql' => [
            'GRAMMAR' => env('DB_GRAMMAR', 'mysql'),
            'HOST' => env('DB_HOST', '127.0.0.1'),
            'USER' => env('DB_USER', 'root'),
            'PASS' => env('DB_PASS', 'root'),
            'PORT' => env('DB_PORT', 3306),
            'ENC' => env('DB_ENCODING', 'UTF8'),
            'DB' => env('DB_DATABASE', 'test'),
        ],
        // add more if you wish
    ];