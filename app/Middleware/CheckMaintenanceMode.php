<?php

    declare(strict_types=1);


    namespace App\Middleware;


    class CheckMaintenanceMode extends BaseMiddleware
    {
        public function handle($app, callable $next)
        {
            $this->app = $app;
            $this->setRequestData('id', 'CheckMaintenanceMode');
            return $next($app);
        }
    }