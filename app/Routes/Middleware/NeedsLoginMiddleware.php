<?php

    declare(strict_types=1);


    namespace App\Routes\Middleware;

    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Request\HttpRequest;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class NeedsLoginMiddleware
    {
        public function handle(HttpRequest $request, Redirect $redirect, ResponseInterface $response)
        {
            $u = $request->auth->user();

            if (!$u) {
                if ($request->expectsJson()) {
                    throw new BoomException(new Boom(HTTPResponseCode::HTTP_FORBIDDEN));
                }

                return $redirect->to('/login');
            }

            return $response;
        }
    }

