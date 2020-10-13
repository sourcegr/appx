<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;



    use App\Http\Auth\AuthManager;

    class AuthServiceProvider extends ServiceProvider
    {
        public function init()
        {
            return function() {
                return new AuthManager();
            };
        }
    }