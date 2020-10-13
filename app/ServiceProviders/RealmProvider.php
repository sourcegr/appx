<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use App\Http\HttpRequest;

    class RealmProvider extends ServiceProvider
    {
        const DEFAULT_REALM = 'WEB';
        const DEFAULT_API_VERSION = 'API';

        public function init()
        {
            return $this;
        }

        public function getService()
        {
            if ($this->app->getRequest()->expectsJson()) {
                return static::DEFAULT_API_VERSION;
//                if ($request->URLstartsWith('api/1')) {
//                    return 'API1';
//                }
//                if ($request->URLstartsWith('api/2')) {
//                    return 'API2';
//                }
            }


            return static::DEFAULT_REALM;
        }
    }