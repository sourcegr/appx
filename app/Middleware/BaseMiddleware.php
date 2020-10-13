<?php

    declare(strict_types=1);


    namespace App\Middleware;


    use Sourcegr\Framework\Base\Interfaces\MiddlewareInterface;

    class BaseMiddleware implements MiddlewareInterface
    {
        protected $app = null;
        protected $name = null;

        public function setName(string $name) {
            $this->name = $name;
        }

        public function handle($app, callable $next)
        {
            $this->app = $app;
        }

        public function setRequestData($name, $value) {
            $this->app->getRequest()->data->$name = $value;
        }
    }