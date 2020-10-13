<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;

    use App\App;

    class ServiceProvider
    {

        /**
         * @var App|null
         */
        public $app = null;

        public function __construct(App $app)
        {
            $this->app = $app;
        }

        public function getService() {
            return null;
        }
    }