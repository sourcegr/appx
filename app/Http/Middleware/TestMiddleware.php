<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Response\ResponseInterface;


    class TestMiddleware extends BaseMiddleware
    {
        public function handle(ResponseInterface $response)
        {
            return $response;
        }

    }