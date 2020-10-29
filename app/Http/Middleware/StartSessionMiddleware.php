<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\ResponseInterface;
    use Sourcegr\Framework\Http\Session\SessionProviderInterface;

    class StartSessionMiddleware extends BaseMiddleware
    {
        public function handle(SessionProviderInterface $sessionProvider, ResponseInterface $response)
        {
            $sessionProvider->startSession();

            // shutdown callback
            $this->app->registerShutdownCallback(function (RequestInterface $request) {
                $request->persistSession();
            });

            return $response;
        }
    }