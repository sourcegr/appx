<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Redirect\PermanentRedirect;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Redirect\TemporaryRedirect;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class TestMiddleware extends BaseMiddleware
    {
        public function handle(ResponseInterface $response)
        {
//            return new Boom(HTTPResponseCode::HTTP_NOT_IMPLEMENTED);
//            return (new TemporaryRedirect('/go/here', true, [], ['Retry-After' => 500]));
//            return (new TemporaryRedirect('go/here'))->withHeader('Retry-After', 500);
//            return (new Redirect('go/here'))->withHeader('Retry-After', 500);
            return $response;
        }

    }