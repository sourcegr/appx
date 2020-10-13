<?php


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Http\Router\Route;
    use Sourcegr\Framework\Http\Router\RouteCollection;
    use Sourcegr\Framework\Http\Router\RouteManager;

    class RouterProvider extends ServiceProvider
    {
        /*
         * $route->add('GET', '/path/to/drive', function() {});
         *
         * $route->controller('/path/to/drive', Controller::class);
         *
         * $route->controller('/path/to/drive', Controller::class);
         *
         */

        public function init()
        {
            return new RouteManager();
        }
    }