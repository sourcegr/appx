<?php

    declare(strict_types=1);


    namespace App\Routes\Middleware;


    use Sourcegr\Framework\Base\Auth\AuthUserProviderManagerInterface;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Request\HttpRequest;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;

    class LoggedInMiddleware
    {
        public function handle(HttpRequest $request) {
            $u = $request->auth->user();

            if (!$u) {
                throw new BoomException(new Boom(HTTPResponseCode::HTTP_FORBIDDEN));
            }
        }
    }

