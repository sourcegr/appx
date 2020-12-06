<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Response\ResponseInterface;


    class SendCookiesMiddleware extends BaseMiddleware
    {
        public function handle(ResponseInterface $response)
        {
            // shutdown callback
            $this->app->registerShutdownCallback(function (ResponseInterface $response) {
                // send cookies if they should be sent
                $response->sendCookies();
            });

            return $response;
        }
    }