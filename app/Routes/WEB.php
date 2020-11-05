<?php

    declare(strict_types=1);

    use App\Http\Controllers\LoginController;
    use App\Routes\Middleware\NeedsLoginMiddleware;
    use App\Routes\Middleware\RedirectIfAuthenticatedMiddleware;
    use Sourcegr\Framework\Base\View\ViewManager;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Router\RouteCollection;


    return function (RouteCollection $routeCollection) {

        $routeCollection->GET('/', function(Redirect $r) {
            return $r->to('/app');
        });


        $routeCollection->GET('/app',function (ViewManager $viewManager) {
            return $viewManager->make('main');
        })->setMiddleware(NeedsLoginMiddleware::class);


        $routeCollection->GET('login', LoginController::class, 'login')->setMiddleware(RedirectIfAuthenticatedMiddleware::class);


        $routeCollection->POST('login', LoginController::class, 'authenticate')->setMiddleware(RedirectIfAuthenticatedMiddleware::class);


        $routeCollection->GET('logout', LoginController::class, 'logout');





        //        $routeCollection->rest('/contact', ContactController::class)
        //            ->allow('get, post, put, delete')
        //            ->exclude('patch');





//        $routeCollection->GET('/plain',
//            function () {
//                return 'Text content';
//            });
//
//        $routeCollection->GET('/array',
//            function () {
//                return ['vagelis'];
//            });
//
//        $routeCollection->GET('/object',
//            function () {
//                return ['id' => 1, 'name' => 'vagelis'];
//            });
//
//        $routeCollection->GET('/500',
//            function () {
//                return new Boom(HTTPResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'CSRF/XSRF parameter missing');
//            });
//
//        $routeCollection->GET('/view',
//            function (ViewManager $viewManager) {
//                return $viewManager->make('main')->with([
//                    'name' => 'papas',
//                    'name1' => 'ge<u>o</u>rge',
//                    'otherName' => 'MIKE!',
//                    'teams' => [
//                        [
//                            'id' => 1,
//                            'data' => ['a', 'b', 'c']
//                        ],
//                        [
//                            'id' => 2,
//                            'data' => ['aa', 'bb', 'cc']
//                        ],
//                    ]
//                ]);
//            });
//
//        $routeCollection->GET('/redirect/permanent',
//            function (PermanentRedirect $redirect) {
//                return $redirect->to('/redirect_response_permanent');
//            });
//
//        $routeCollection->GET('/redirect/temp',
//            function (PermanentRedirect $redirect) {
//                return $redirect->to('/redirect_response_temporary');
//            });
//
//        $routeCollection->GET('/redirect_response_permanent',
//            function () {
//                return 'Got here from permanent redirect';
//            });
//
//        $routeCollection->GET('/redirect_response_temporary',
//            function () {
//                return 'Got here from temporary redirect';
//            });
    };