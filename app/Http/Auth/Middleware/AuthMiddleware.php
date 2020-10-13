<?php

    declare(strict_types=1);


    namespace App\Http\Auth\Middleware;


    use App\App;
    use App\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Session\SessionHandler;

    class AuthMiddleware extends BaseMiddleware
    {
        /**
         * @param App      $app
         * @param callable $next
         */
        public function handle($app, callable $next)
        {
            parent::handle($app, $next);
            $request = $app->getRequest();

            $config = $app->loadConfig('auth')['REALMS'][$request->realm];

            $driverConf = $config['DRIVER'];
            $userProviderConf = $config['USER_PROVIDER'];

            $driver = new $driverConf($app);
            $userProvider = new $userProviderConf['CLASS']($app, $userProviderConf['CONFIG']);

            $authService = $app->getService('auth');
            $authService->setDriver($driver);
            $authService->setUserProvider($userProvider);

            $user = $authService->getLoggedInUser();

            $this->setRequestData('auth', $authService);
        }
    }