<?php

    declare(strict_types=1);

    return [
        'namespaces' => [
            // namespace => dir_to_take_vies_from
        ],
        'globals' => [
            //name => value
        ],
        'views' => app()::APP_STORAGE_PATH.'/Views',
        'cache' => app()::APP_STORAGE_PATH.'/cache/views'
    ];