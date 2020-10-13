<?php

    declare(strict_types=1);

    use App\App;
    require '../vendor/autoload.php';


    $app = App::create();
    $app->bootstrap();


    $response = $app->kickStart(
        $request = $app->getService('request')
    );


    $app->terminate($request, $response);



    /**
     * files
     *
     * $storage = $app->getService('fileEngine');
     * $drive = $storage->drive('root');
     * $res = $drive->isReadable('newDir');
     * var_dump([$res, $drive->getDirectoryList()]);
     */


    /**
     * routes
     */

//    $routes = new RouteCollection();
//    $routes->group('/root', function() {
//
//    });

//    $routes->on('API', function ($route) {
//        $route->groupBy('/api', function ($route) {
//            $route->get('/contact', function () {
//                echo 'GET Contact';
//            });
//            $route->post('/contact', function () {
//                echo 'GET Contact';
//            });
//        });
//
//        $route->post('/all', function () {
//            echo 'GET Contact';
//        });
//
//        $route->get('/all', function () {
//            echo 'GET Contact';
//        });
//
//    });
//
//    $routes->on('API', function ($route) {
//        $route->groupBy('/api', function ($route) {
//            $route->get('/contact', function () {
//                echo 'GET Contact';
//            });
//            $route->post('/contact', function () {
//                echo 'GET Contact';
//            });
//        });
//
//        $route->post('/all', function () {
//            echo 'GET Contact';
//        });
//
//        $route->get('/all', function () {
//            echo 'GET Contact';
//        });
//
//    });
//
//
//    $routes->compile();
//
//    var_dump($routes->routes['API']);
//    die();
//
//
//




//    $req->
//    $session->addFlash('message', 'error');
//    var_dump($_SESSION);

    $app->tearDown();


    //    $c = Contact::all();
    //    var_dump($c);

    //
    //    $request = new Sourcegr\Http\Request\Request();
    //
    //    $response = $app->init($request);


