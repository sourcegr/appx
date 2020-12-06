<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\ResponseInterface;


    class AuthenticateSessionMiddleware extends BaseMiddleware
    {
        public function handle(ResponseInterface $response, RequestInterface $r)
        {
            $r->user = $r->auth->user();
            return $response;
        }
    }