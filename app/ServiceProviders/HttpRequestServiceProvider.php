<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;

    use App\Http\HttpRequest;

    class HttpRequestServiceProvider extends ServiceProvider
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

            return $this->service = HttpRequest::fromHTTP();
        }
    }