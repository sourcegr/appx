<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;



    use Sourcegr\Framework\Base\ServiceProvider;

    class LoggingServiceProvider extends ServiceProvider
    {
        protected $config = null;

        public function register()
        {
            $this->config = $this->loadConfig('logging');
        }


        public function boot()
        {
            $default = $this->config['default'];
        }
    }