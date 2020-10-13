<?php

    declare(strict_types=1);

    use App\App;

    return [
        'database' => env('DB_DRIVER', 'mysql'),
        'env' => env('APP_ENV', 'dev'),
        'maintenance_file' => env('APP_MAINTENANCE', App::APP_STORAGE_PATH . 'internal/framework_is_down'),
    ];
