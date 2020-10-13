<?php

    declare(strict_types=1);


    namespace App\Middleware;


    class RandomRouteMiddleware extends BaseMiddleware
    {
        public function handle($app, callable $next)
        {
            $this->app = $app;
            $this->setRequestData('id', 'RandomRouteMiddleware');
            return $next($app);
        }
    }