<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\App\ContainerInterface;
    use Sourcegr\Framework\Base\Encryptor\Encryptor;
    use Sourcegr\Framework\Base\Encryptor\EncryptorInterface;
    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class EncryptorServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->container->singleton(
                EncryptorInterface::class,
                function (ContainerInterface $container) {
                    return new Encryptor(
                        $container->get('CONFIG')['app_key'],
                        $container->get('CONFIG')['encryption_cipher']
                    );
                });
        }

        public function boot(ContainerInterface $container, RequestInterface $request, ResponseInterface $response, EncryptorInterface $encryptor){
            if ($container->get('CONFIG')['encrypt_cookies']) {
                $request->setEncryptorEngine($encryptor);
                $response->setEncryptorEngine($encryptor);
            }
        }
    }