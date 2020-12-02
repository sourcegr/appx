<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class CheckApiToken extends BaseMiddleware
    {
        public function handle(ResponseInterface $response, RequestInterface $request)
        {
            $request->user = $request->auth->user();
            if (!$request->user) {
                return new Boom(HTTPResponseCode::HTTP_UNAUTHORIZED, 'Unauthorized');
            }
            return $response;
        }
    }