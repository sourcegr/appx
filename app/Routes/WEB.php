<?php

    declare(strict_types=1);

    use Sourcegr\Framework\Base\View\ViewManager;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Redirect\PermanentRedirect;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Router\RouteCollection;

    return function (RouteCollection $routeCollection) {
//        $routeCollection->rest('/contact', ContactController::class)
//            ->allow('get, post, put, delete')
//            ->exclude('patch');

        $routeCollection->GET('/plain',
            function () {
                return 'Text content';
            });

        $routeCollection->GET('/array',
            function () {
                return ['vagelis'];
            });

        $routeCollection->GET('/object',
            function () {
                return ['id' => 1, 'name' => 'vagelis'];
            });

        $routeCollection->GET('/500',
            function () {
                return new Boom(HTTPResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'CSRF/XSRF parameter missing');
            });

        $routeCollection->GET('/view',
            function (ViewManager $viewManager) {
                return $viewManager->make('main')->with([
                    'name' => 'papas',
                    'name1' => 'ge<u>o</u>rge',
                    'otherName' => 'MIKE!',
                    'teams' => [
                        [
                            'id' => 1,
                            'data' => ['a', 'b', 'c']
                        ],
                        [
                            'id' => 2,
                            'data' => ['aa', 'bb', 'cc']
                        ],
                    ]
                ]);
            });

        $routeCollection->GET('/redirect/permanent',
            function (PermanentRedirect $redirect) {
                return $redirect->to('/redirect_response_permanent');
            });

        $routeCollection->GET('/redirect/temp',
            function (PermanentRedirect $redirect) {
                return $redirect->to('/redirect_response_temporary');
            });

        $routeCollection->GET('/redirect_response_permanent',
            function () {
                return 'Got here from permanent redirect';
            });

        $routeCollection->GET('/redirect_response_temporary',
            function () {
                return 'Got here from temporary redirect';
            });
    };