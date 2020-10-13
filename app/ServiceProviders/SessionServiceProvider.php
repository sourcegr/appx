<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Http\Session\SessionHandler;

    class SessionServiceProvider extends ServiceProvider
    {
        protected $service;

        public function init()
        {
            return $this;
        }

        public function getService() {
            if ($this->service) {
                return $this->service;
            }

            $config = $this->app->loadConfig('session');

            $configName = $config['driver'];
            $driverName = $config['drivers'][$configName]['class'];

            if (!class_exists($driverName)) {
                throw new \Exception('SessionServiceProvider: class doesn\'t exist');
            }

            $driver = new $driverName($config);

            return $this->service = new SessionHandler($driver);
        }

        public function setCookieParams($config)
        {
            // do something
        }
    }