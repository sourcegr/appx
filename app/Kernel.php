<?php

    declare(strict_types=1);


    namespace App;

    use Closure;
    use Sourcegr\Framework\App\AppInterface;
    use Sourcegr\Framework\App\ContainerInterface;
    use Sourcegr\Framework\App\KernelInterface;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Response\HeaderBag;
    use Sourcegr\Framework\Http\Response\HttpResponse;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Router\Route;
    use Sourcegr\Framework\Http\Router\RouteMatchInterface;


    class Kernel implements KernelInterface
    {
        protected $app;

        public function __construct(AppInterface $app)
        {
            $this->app = $app;
        }

        public function handleOPTIONS()
        {
            $this->response = new HttpResponse(new HeaderBag());
            $this->response->addHeaders($this->app->appConfig['CORS']);
            $this->response->setFromHTTPResponseCode(HTTPResponseCode::HTTP_NO_CONTENT);

            // no need to prepareForShutdown. Just shutDown NOW
            $this->app->shutDown();
        }

        public function handleRoute(RouteMatchInterface $routeMatch)
        {
            /** @var ContainerInterface $container */
            $container = $this->app->container;

            /** @var array $vars */
            $vars = $routeMatch->vars;

            /** @var Route $route */
            $route = $routeMatch->route;

            $predicates = $route->getCompiledParam('predicates');

            if (count($predicates)) {
                foreach ($predicates as $predicate) {
                    $result = $routeMatch->predicateCompiler->runPredicate($predicate, $routeMatch);
                    $this->checkForBoom($result);
                }
            }

            $callback = $route->callback;


            try {
                if (is_string($callback)) {
                    $handlerResult = $container->makeFromString($callback, $vars);
                } elseif ($callback instanceof Closure) {
                    $handlerResult = $container->call($callback, $vars);
                }
            } catch(\ReflectionException $e) {
                $m = explode("@", $callback)[1] ?? $callback;
                $handlerResult = new Boom(HTTPResponseCode::HTTP_NOT_IMPLEMENTED, "method $m is not implemented (it should, though)");
            } catch(BoomException $e) {
                $handlerResult = $e->boom;
            }


            $this->checkForBoom($handlerResult);

            $this->app->response->responseContent = $handlerResult;
        }


        public function checkForBoom($response)
        {
            if (!($response instanceof Boom)) {
                return;
            }

            /** @var Boom $boom */
            $boom = $response;

            // check if result has any flash data, and add them to the flashBag
            $this->app->request->addFlash($boom->getFlash());

            // set the response to Boom! It will be handled in shutDown()
            $this->app->response->statusCode = $boom->statusCode;
            $this->app->response->addHeaders($boom->headers);
            $this->app->response->responseContent = $boom;


            if (!$boom->haltsExecution()) {
                // run callbacks before closing!
                $this->app->prepareForShutdown();
            }

            $this->app->shutDown();
        }

        public function execMiddleware()
        {
            $response = $this->app->response;

            $groups = func_get_args();

            foreach ($groups as $middlewares) {
                //ensure it is an array
                if (!is_array($middlewares)) {
                    $middlewares = [$middlewares];
                }

                foreach ($middlewares as $middleware) {
                    if ($this->app->middlewareBooted($middleware)) {
                        continue;
                    }

                    $response = $this->app->container->call("$middleware@handle", ['response' => $response]);
                    $this->checkForBoom($response);
                }
            }

            $this->response = $response;
        }
    }


    /*
    protected $serviceProviders = [];

    public $services;


    public $app;


    protected function initServiceProvider($serviceProvider)
    {
//            echo "<BR>init $serviceProvider"
        return (new $serviceProvider($this->app))->init();
    }



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
    */