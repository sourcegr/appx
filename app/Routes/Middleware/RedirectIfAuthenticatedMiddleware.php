<?php

    namespace App\Routes\Middleware;


    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;

    class RedirectIfAuthenticatedMiddleware
    {
        public function handle(RequestInterface $request, Redirect $redirect)
        {
//            dd($request->user);
            if ($request->user) {
                if ($request->expectsJson()) {
                    throw new BoomException(new Boom(HTTPResponseCode::HTTP_FORBIDDEN));
                }

//                dd('a');
                return $redirect->to('/app');
            }
        }
    }