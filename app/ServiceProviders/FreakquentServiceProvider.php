<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use InvalidArgumentException;
    use App\App;
    use Sourcegr\Framework\Database\Freakquent\Freakquent;

    class FreakquentServiceProvider extends ServiceProvider
    {
        protected $service = null;

        public function init()
        {
            return $this;
        }

        public function getService()
        {
            if ($this->service) {
                return $this->service;
            }

            $databaseConfig = $this->app->conf('database');
            $dbParams = $this->app->loadConfig('database');

            $config = $dbParams[$databaseConfig] ?? null;

            if (!$config) {
                throw new InvalidArgumentException('please set connection settings for ' . $databaseConfig . ' in /config/database.php');
            }

            return $this->service = Freakquent::init($databaseConfig, $config);
        }
    }