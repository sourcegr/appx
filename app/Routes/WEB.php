<?php

    declare(strict_types=1);

    use App\Controllers\LoginController;
    use App\Routes\Middleware\NeedsLoginMiddleware;
    use App\Routes\Middleware\RedirectIfAuthenticatedMiddleware;
    use Sourcegr\Framework\Base\View\ViewManager;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Router\RouteCollection;


    return function (RouteCollection $routeCollection) {
        $routeCollection->GET('/', function (ViewManager $viewManager, RequestInterface $request) {
            return $viewManager->make('main');
        });

        $routeCollection->GET('/secret', function (ViewManager $viewManager, RequestInterface $request) {
            return $viewManager
                ->make('secret')
                ->with('user', $request->user);
            }
        )->setMiddleware(NeedsLoginMiddleware::class);


        $routeCollection
            ->GET('login', LoginController::class, 'login')
            ->setMiddleware(RedirectIfAuthenticatedMiddleware::class);

        $routeCollection
            ->POST('login', LoginController::class, 'authenticate')
            ->setMiddleware(RedirectIfAuthenticatedMiddleware::class);

        $routeCollection
            ->GET('logout', LoginController::class, 'logout');
    };