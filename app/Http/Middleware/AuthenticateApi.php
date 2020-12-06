<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use App\Providers\UsersProvider;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;


    class AuthenticateApi extends BaseMiddleware
    {
        public function handle(ResponseInterface $response, RequestInterface $request, UsersProvider $usersProvider)
        {
            $u = $request->user;

            if (!$request->user) {
                return new Boom(HTTPResponseCode::HTTP_UNAUTHORIZED, 'Unauthorized');
            }

            $request->user['permissions'] = $usersProvider->getUserPermissions($u['id']);
            return $response;
        }
    }