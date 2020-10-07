<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use InvalidArgumentException;
    use App\App;
    use Sourcegr\Framework\Database\Freakquent\Freakquent;

    class FreakquentServiceProvider extends ServiceProvider
    {
        /**
         * @var App null
         */
        public $app = null;

        /**
         * @return \Closure
         */
        public function init()
        {
            $databaseConfig = $this->app->conf('database');
            $dbParams = $this->app->loadConfig('database');
            $config = $dbParams[$databaseConfig] ?? null;

            if (!$config) {
                throw new InvalidArgumentException('please set connection settings for '. $databaseConfig.' in /config/database.php');
            }

            $db = Freakquent::init($databaseConfig, $config);

            return function() use ($db) {
                return $db;
            };
        }
    }