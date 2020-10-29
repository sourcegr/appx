<?php

    declare(strict_types=1);


    use App\App;

    return [
        'root' => [
            'engine' => 'local',
            'path' => App::APP_STORAGE_PATH,
            'name' => 'Drive.local'
        ]
    ];
