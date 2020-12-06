<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\ServiceProvider;


    class LoggingServiceProvider extends ServiceProvider
    {
        protected $config = null;

        public function register()
        {
            // fixme todo
            $this->config = $this->loadConfig('logging');
        }


        public function boot()
        {
            // fixme todo
            $default = $this->config['default'];
        }
    }