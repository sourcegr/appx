<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;



    use Sourcegr\Framework\Http\Session\SessionHandler;

    class SessionServiceProvider extends ServiceProvider
    {
        public function init()
        {
            $config = $this->app->loadConfig('session');

            $configName = $config['driver'];
            $driverName = $config['drivers'][$configName]['class'];

//            die($driverName);
            if (!class_exists($driverName)) {
                throw new \Exception('SessionServiceProvider: class doesn\'t exist');
            }

            $driver = new $driverName($this->app, $config);

            return new SessionHandler($this->app, $driver);
        }

        public function setCookieParams($config) {
            // do something
        }
    }