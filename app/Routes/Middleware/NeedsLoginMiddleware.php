<?php

    declare(strict_types=1);


    namespace App\Routes\Middleware;

    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Request\HttpRequest;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;

    class NeedsLoginMiddleware
    {
        public function handle(HttpRequest $request, Redirect $redirect)
        {
            $u = $request->user;

            if (!$u) {
                if ($request->expectsJson()) {
                    throw new BoomException(new Boom(HTTPResponseCode::HTTP_FORBIDDEN));
                }

                return $redirect->to('/login');
            }
        }
    }

