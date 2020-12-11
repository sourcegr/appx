<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Base;


    class RedisConnection
    {
        protected $connection;


        public function __construct($config)
        {
            $redis = new \Redis();

            $host = (($config['tls'] ?? false) ? 'tls://' : '') . ($config['host'] ?? '127.0.0.1');
            $port = (int)($config['port'] ?? 6379);
            $timeout = (int)($config['timeout'] ?? 3);

            $redis->connect($host, $port, $timeout);

            $this->connection = $redis;
        }


        /**
         * @return \Redis
         */
        public function getConnection(): \Redis
        {
            return $this->connection;
        }
    }