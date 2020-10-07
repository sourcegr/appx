<?php

    declare(strict_types=1);

    namespace App;

    use Sourcegr\Framework\Base\Kernel;
    use Sourcegr\Framework\Base\ParameterBag;

    use function PHPUnit\Framework\throwException;


    class App
    {
        public const APP_BASE_PATH = __DIR__ . '/../';
        public const APP_APP_PATH = self::APP_BASE_PATH . 'app/';
        public const APP_STORAGE_PATH = self::APP_BASE_PATH . 'storage/';
        public const APP_CONFIG_PATH = self::APP_BASE_PATH . 'config/';
        public const APP_WEB_PATH = self::APP_BASE_PATH . '../www/';

        private $config;
        private $services;
        private $middlewares;
        private $shutDownCallbacks = [];

        private $router;

        private $response = '';

        public $env = 'dev';
        public $kernel;


        public function __construct()
        {
            $this->middlewares = new ParameterBag();
            $this->services = new ParameterBag();
        }

        public function conf($key = null)
        {
            return $key ? $this->config[$key] ?? null : $this->config;
        }

        public function loadConfig($config)
        {
            return require static::APP_CONFIG_PATH . "$config.php";
        }

        public function bootstrap()
        {
            // get app-wide configuration
            $this->config = self::loadConfig('app');

            // set app environment
            $this->env = $this->config['env'];

            if (!$this->config) {
                throw new \Exception('no config');
            }

            $this->kernel = new Kernel($this);

            // register all service providers
            $this->services->addIfExists(
                $a = $this->kernel->registerServiceProviders(self::loadConfig('serviceProviders'))
            );

            // register all middleware
            $this->middlewares->addIfExists(
                $this->kernel->registerMiddlewares(self::loadConfig('middleware'))
            );

            // init all service providers
            $this->kernel->initServiceProviders();

            // init all middleware
            $this->kernel->initMiddlewares();
        }

        public function serviceInited($name, $service, $tag = null)
        {
            if (!$name) {
                throw new \Exception('s');
            }

            $registered = $this->services->get($name) ?? [];

            if ($tag) {
                $registered[$tag] = $service;
            } else {
                $registered[] = $service;
            }

            $this->services->add([$name => $registered]);
        }

        public function getService($name, $tag = null)
        {
            $services = $this->services->get($name) ?? null;

            if (!$services) {
                throw new \Exception('could not get service stack ' . $name);
            }

            $service = $tag ? $services[$tag] : $services[0];
            return is_callable($service) ? $service() : $service;
        }

        public function registerShutdownCallback($callable) {
            $this->shutDownCallbacks[] = $callable;
        }










        //
        //
        //
        //

        public function tearDown() {
            foreach ($this->shutDownCallbacks as $callback) {
                $callback($this->response);
            }
        }
        public function kickStart($request)
        {
            $this->request = $request;
//            $response = $this->kernel->applyMiddleware($request);
//            return $response;
        }


    }