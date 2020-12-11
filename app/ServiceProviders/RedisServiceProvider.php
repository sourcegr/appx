<?php

    declare(strict_types=1);

    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\RedisConnection;
    use Sourcegr\Framework\Base\ServiceProvider;


    class RedisServiceProvider extends ServiceProvider
    {
        public function register()
        {
        }


        public function boot()
        {
            $this->container->singleton(RedisConnection::class, function () {
                $config = $this->loadConfig('redis');

                $redis = new RedisConnection($config);
                return $redis;
            });
        }
    }