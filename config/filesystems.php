<?php

    declare(strict_types=1);


    use App\App;

    return [
        'manager' => Sourcegr\Framework\Filesystem\FileSystemManager::class,
        'drives' => [
            'root' => [
                'engine' => \Sourcegr\Framework\Filesystem\Engines\FileSystemDrive::class,
                'path' => App::APP_STORAGE_PATH
            ]
        ]
    ];
