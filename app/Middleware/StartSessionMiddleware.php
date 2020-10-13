<?php

    declare(strict_types=1);


    namespace App\Middleware;


    class StartSessionMiddleware extends BaseMiddleware
    {
        public function handle($app, callable $next)
        {
            $app->getService('session');
            return $next($app);
        }
    }