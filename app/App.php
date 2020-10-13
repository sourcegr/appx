<?php

    declare(strict_types=1);

    namespace App;

    use App\Http\HttpRequest;

    use Sourcegr\Framework\Base\Helpers\Arr;
    use Sourcegr\Framework\Base\ParameterBag;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Router\RouteManager;


    class App
    {
        public const APP_BASE_PATH = __DIR__ . '/../';
        public const APP_APP_PATH = self::APP_BASE_PATH . 'app/';
        public const APP_STORAGE_PATH = self::APP_BASE_PATH . 'storage/';
        public const APP_CONFIG_PATH = self::APP_BASE_PATH . 'config/';
        public const APP_WEB_PATH = self::APP_BASE_PATH . '../www/';

        public static $instance;

        protected $config;
        protected $middlewares;
        protected $shutDownCallbacks = [];
        protected $routes;

        /**
         * @var RouteManager $router
         */
        protected $router;

        /**
         * @var HttpRequest $request
         */
        protected $request;

        /**
         * @var HttpResponse $response
         */
        protected $response = '';

        public $env = 'dev';

        /**
         * @var Kernel $kernel
         */
        public $kernel;


        # methods
        public static function create(): App
        {
            static::$instance = static::$instance ?? new static();
            return static::$instance;
        }

        public static function getInstance(): App
        {
            return static::create();
        }


        /**
         * @return HttpRequest
         */
        public function getRequest(): HttpRequest
        {
            return $this->request;
        }


        protected function __construct()
        {
            $this->middlewares = new ParameterBag();
            static::$instance = $this;
        }

        public function conf($key = null)
        {
            return $key ? $this->config[$key] ?? null : $this->config;
        }

        public function loadConfig($config)
        {
            return require static::APP_CONFIG_PATH . "$config.php";
        }


        public function inMaintenance(): bool
        {
            return is_file($this->conf('maintenance_file'));
        }

        public function bootstrap()
        {
            # get app-wide configuration
            $this->config = $this->loadConfig('app');

            if (!$this->config) {
                throw new \Exception('no config');
            }

            // get env
            $this->env = $this->config['env'];

            # set app environment
            $this->kernel = new Kernel($this);


            # register all service providers
            $this->kernel->registerServiceProviders($this->loadConfig('serviceProviders'));


            # init all service providers
            $this->kernel->initServiceProviders();
        }


        public function getService($name, $tag = null)
        {
            return $this->kernel->getService($name, $tag);
        }

        public function registerShutdownCallback($callable)
        {
            $this->shutDownCallbacks[] = $callable;
        }










        //
        //
        //
        //



        public function kickStart($request)
        {
            # get request service
            $this->request = $request;
            $this->request->realm = $this->getService('realm');
//            dd($this->kernel->services);


            # get the router manager object
            $routesCallback = require $this::APP_APP_PATH . 'Routes/' . $this->request->realm . '.php';

            $this->router = $this->getService('router');
            $this->router->loadRoutes(
                $this->request->realm,
                $routesCallback
            );

            /** @var \Sourcegr\Framework\Http\Router\RouteMatch $route */
            $routeMatch = $this->router->matchRoute($this->request);


            # probably no match
            if ($routeMatch instanceof Boom) {
                return $routeMatch;
            }

            # We have a route, we can go on
            $middleWareConfig = $this->loadConfig('middleware');

            # combine global and realm middleware
            $middlewaresStack = Arr::merge(
                $middleWareConfig['GLOBAL'],
                $middleWareConfig['REALM'][$this->request->realm] ?? []
            );

            $routeMiddlewares = $routeMatch->route->getMiddleware();

            foreach ($routeMiddlewares as $middlewareName) {
                if ($config = $middleWareConfig['ROUTE'][$middlewareName]) {
                    $middlewaresStack = Arr::merge($middlewaresStack, Arr::ensureArray($config));
                } else {
                    throw new \Exception('Route Middleware not found');
                }
            }

            try {
                $this->kernel->applyMiddlewares($middlewaresStack);
            } catch (BoomException $boom) {
                return $boom->boom;
            }
            return 'a';

//            dd($result);
            /*
             * $handler = $route->getCompiledParam('callback');
             * $handler($request);
             * die('-');
             */
        }

        public function tearDown()
        {
            foreach ($this->shutDownCallbacks as $callback) {
                $callback($this->request, $this->response);
            }
        }

        public function terminate($request, $response)
        {
            $auth = $this->getRequest()->data->auth;
            dd($auth->authenticate(['email' => 'papas@source.gr']));
            dd([$request, $response]);
        }
    }