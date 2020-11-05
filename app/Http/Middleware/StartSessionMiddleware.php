<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Base\View\ViewManager;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\ResponseInterface;
    use Sourcegr\Framework\Http\Session\SessionProviderInterface;

    class StartSessionMiddleware extends BaseMiddleware
    {
        public function handle(RequestInterface $request, SessionProviderInterface $sessionProvider, ViewManager $viewManager, ResponseInterface $response)
        {
            $session = $sessionProvider->startSession();
            $viewManager->addGlobalParam('APPX_CSRF_TOKEN_VALUE', $session->getCSRF());
            $viewManager->addGlobalParam('APPX_CSRF_TOKEN_NAME', $session->getCSRFFieldName());

            // shutdown callback
            $this->app->registerShutdownCallback(function (RequestInterface $request) {
                $request->session->regenerateToken();
                $request->persistSession();
//                dd($request->session);
            });
//            dd($request->auth, 1111);

            return $response;
        }
    }