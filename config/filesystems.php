<?php

    declare(strict_types=1);


    use App\App;

    return [
        'root' => [
            'engine' => \Sourcegr\Framework\Filesystem\Engines\FileSystemDrive::class,
            'path' => App::APP_APP_PATH
//          'path' => App::APP_STORAGE_PATH
        ]
    ];
