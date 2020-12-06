<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;

    use Sourcegr\Framework\App\AppInterface;
    use Sourcegr\Framework\Base\Encryptor\EncryptorInterface;
    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Http\Session\SessionInterface;
    use Sourcegr\Framework\Http\Session\SessionProvider;
    use Sourcegr\Framework\Http\Session\SessionProviderInterface;


    class SessionServiceProvider extends ServiceProvider
    {
        protected $config = null;


        public function register()
        {
            $this->container->bind(SessionProviderInterface::class, SessionProvider::class, true);
        }


        public function boot(SessionProviderInterface $sp, AppInterface $app) //ResponseInterface $response)
        {
            $config = $this->loadConfig('session');
            $app->container->get('RESPONSE')->setSessionCookieParams($config);

            if ($app->appConfig['encrypt_cookies']) {
                $encryptor = $app->container->get(EncryptorInterface::class);
                $app->request->cookie->setEncryptorEngine($encryptor);
                $app->response->setCookieBag($app->request->cookie);
            }

            $this->container->instance(SessionInterface::class, $sp->init($config));
        }
    }