<?php

    declare(strict_types=1);


    namespace App;


    use App\Http\HttpRequest;
    use App\ServiceProviders\ServiceProvider;
    use Sourcegr\Framework\Base\ParameterBag;
    use Sourcegr\Framework\Http\BoomException;


    class Kernel
    {
        protected $serviceProviders = [];

        public $services;


        /**
         * @var App
         */
        public $app;


        protected function initServiceProvider($serviceProvider)
        {
//            echo "<BR>init $serviceProvider"
            return (new $serviceProvider($this->app))->init();
        }


        // public

        /**
         * @param             $config
         *
         * @throws BoomException
         * @return mixed
         */
        public function applyMiddlewares($config)
        {
            //

            $namedKeys = array_keys($config);
            $config = array_values($config);

            $getNext = function ($config, $index) use ($namedKeys, &$getNext) {
                $callback = $config[$index] ?? null;
                if (!$callback) {
                    return function ($app) {
                        return $app;
                    };
                }

                $next = $getNext($config, $index + 1);

                if (is_callable($callback)) {
                    return function ($app) use ($callback, $next) {
                        return $callback($app, $next);
                    };
                }
                return function ($app) use ($callback, $next, $index, $namedKeys) {
                    $m = new $callback($app);
//                    $m = new $callback();
                    $name = is_numeric($namedKeys[$index]) ? basename(str_replace('\\',
                        '/',
                        $callback)) : $namedKeys[$index];

                    $m->setName($name);
                    return $m->handle($app, $next);
                };
            };

            return $getNext($config, 0)($this->app);
        }


        public function __construct($app)
        {
            $this->services = new ParameterBag();
            $this->app = $app;
        }

        public function getService(string $name, $tag = null)
        {
            $services = $this->services->get($name) ?? null;

            if (!$services) {
                throw new \Exception('could not get service stack ' . $name);
            }

            $service = $tag ? $services[$tag] : $services[0];

            if ($service instanceof ServiceProvider) {
                return $service->getService();
            };

            if (is_callable($service)) {
                return $service();
            }
            return $service;
        }



        public function registerServiceProviders($config)
        {
            $toRegister = [];

            foreach ($config as $name => $serviceProviderDefinition) {
                $this->serviceProviders[$name] = $serviceProviderDefinition;
                if (($serviceProviderDefinition['immediate'] ?? false)) {
                    $toRegister[$name] = $serviceProviderDefinition;
                }
            }


            foreach ($toRegister as $name => $serviceProviderDefinition) {
                $res = $this->initServiceProvider($serviceProviderDefinition['class']);
                $this->markServiceInited($name, $res);
            }
        }

        public function initServiceProviders()
        {
            foreach ($this->serviceProviders as $name => $serviceProviderDefinition) {
                if (!($serviceProviderDefinition['immediate'] ?? false)) {
                    $res = $this->initServiceProvider($serviceProviderDefinition['class']);
                    if ($res) {
                        if (is_array($res)) {
                            $tag = array_keys($res)[0];

                            $this->markServiceInited($name,
                                $res[$tag],
                                $tag);
                        } else {
                            $this->markServiceInited($name, $res);
                        }
                    }
                }
            }
        }

        public function markServiceInited(string $name, $service, ?string $tag = null): void
        {
            if (!$name) {
                throw new \Exception('service should have a name');
            }


            $registered = $this->services->get($name) ?? [];

            if ($tag) {
                $registered[$tag] = $service;
            } else {
                $registered[] = $service;
            }

            $this->services->set($name, $registered);
        }
    }