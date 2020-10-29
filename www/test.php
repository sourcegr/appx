<?php

    use Sourcegr\Framework\Appex\Appx;
    use Sourcegr\Framework\Appex\ViewManager;

    require '../vendor/autoload.php';



    $v = new ViewManager(__DIR__ . '/../storage/Views', __DIR__ . '/../storage/cache/views');
    $view = $v->make('main');

//    $view->with('name', 'papas')
//        ->with('name1', 'ge<u>o</u>rge')
//        ->with('otherName', 'MIKE!')
//        ->with('teams', [
//            [
//                'id' => 1,
//                'data' => ['a', 'b', 'c']
//            ],[
//                'id' => 2,
//                'data' => ['aa', 'bb', 'cc']
//            ],
//        ]);
    $view->with([
        'name' => 'papas',
        'name1' => 'ge<u>o</u>rge',
        'otherName' => 'MIKE!',
        'teams' => [
            [
                'id' => 1,
                'data' => ['a', 'b', 'c']
            ],[
                'id' => 2,
                'data' => ['aa', 'bb', 'cc']
            ],
        ]
    ]);

    $view->render();