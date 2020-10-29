<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Database\DBConnectionManager;
    use Sourcegr\Framework\Database\DBConnectionManagerInterface;
    use Sourcegr\Framework\Database\PDOConnection\PDOConnection;
    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Database\QueryBuilder\DBInterface;

    class DBServiceProvider extends ServiceProvider
    {
        protected $config = null;

        public function register()
        {
            $this->config = $this->loadConfig('database');

            $this->container->singleton(DBConnectionManagerInterface::class, DBConnectionManager::class);
            $this->container->alias(DBConnectionManagerInterface::class, 'DBManager');
//
//            $this->container->bind(PDOConnection::class);
//            $this->container->alias(PDOConnection::class, 'DB.PDO');
        }


        public function boot(DBConnectionManagerInterface $db)
        {
            $default = $this->config['default'];

            // register the default, if any
            if ($default && $conf = $this->config['providers'][$default]) {
                $connection = $db->create('default', $conf['engine'], $conf);
                $this->container->instance(PDOConnection::class, $connection);
                $this->container->instance(DBInterface::class, DB::class);
                $this->container->alias(PDOConnection::class, 'DB.connections.default');
            }

            foreach ($this->config['providers'] as $cName => $cConf) {
                if ($cName !== 'default' && ($cConf['active'] ?? false)) {
                    $this->container->singleton(
                        'DB.connections.' . $cName,
                        function ($container) use ($cName, $cConf) {
                            return $container->get('DBManager')->create($cName, $cConf['engine'], $cConf);
                        });
                }
            }
        }
    }