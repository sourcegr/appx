<?php


    declare(strict_types=1);

    $time_start = microtime(true);
    $rustart = getrusage();

    use App\App;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;

    require '../vendor/autoload.php';

    /**
     * @return App
     */
    function app() {
        global $app;
        return $app;
    }


//    Sentry\init(['dsn' => 'https://e72692eadd4d4dec920f7ca09987f738@o467268.ingest.sentry.io/5493407' ]);
//    throw new BoomException(new Boom(404), "Got boom!");



    $app = new App();
    $app->init(
        \Sourcegr\Framework\Http\Request\HttpRequest::fromHTTP()
    );



    function ss(...$args) {
        $str = debug_backtrace()[0]['file'] . ':' . debug_backtrace()[0]['line'];
        $args[] = $str;
        dd(...$args);
    }










function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

    $ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls<br><br><br>";


$time = number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5, '.', '');
echo "REQUEST_TIME_FLOAT: $time ms<br><br>";

$time_end = microtime(true);
$execution_time = number_format((float)(($time_end - $time_start)), 10);
echo '<b>Total Execution Time:</b> '.$execution_time.'sec';

//    $app->bootstrap();
//
//
//    $response = $app->kickStart(
//        $request = $app->getService('request')
//    );
//
//
//    $app->terminate($request, $response);



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

//    $app->tearDown();


    //    $c = Contact::all();
    //    var_dump($c);

    //
    //    $request = new Sourcegr\Http\Request\Request();
    //
    //    $response = $app->init($request);


