<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\HttpRequest;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class CreateFreshApiTokenMiddleware extends BaseMiddleware
    {
        public function handle(HttpRequest $request, ResponseInterface $response)
        {
            // shutdown callback
            $this->app->registerShutdownCallback(function () use ($request) {
                $request->session->regenerateToken();
            });
            return $response;
        }
    }