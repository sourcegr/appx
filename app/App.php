<?php

    declare(strict_types=1);

    namespace App;

    use App\ServiceProviders\RealmFinder;
    use Dotenv\Dotenv;
    use Sourcegr\Framework\App\App as MainApp;
    use Sourcegr\Framework\App\AppInterface;
    use Sourcegr\Framework\App\ContainerInterface;
    use Sourcegr\Framework\App\KernelInterface;
    use Sourcegr\Framework\Base\Encryptor\Encryptor;
    use Sourcegr\Framework\Base\Encryptor\EncryptorInterface;
    use Sourcegr\Framework\Http\Request\HttpRequest;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HttpResponse;
    use Sourcegr\Framework\Http\Response\ResponseInterface;
    use Sourcegr\Framework\Http\Router\RouteManagerInterface;

    class App extends MainApp
    {
        public const APP_BASE_PATH = __DIR__ . '/../';
        public const APP_APP_PATH = self::APP_BASE_PATH . 'app/';
        public const APP_STORAGE_PATH = self::APP_BASE_PATH . 'storage/';
        public const APP_CONFIG_PATH = self::APP_BASE_PATH . 'config/';
        public const APP_WEB_PATH = self::APP_BASE_PATH . '../www/';

        /**
         * @var KernelInterface $kernel
         */
        protected $kernel;

        public function __construct()
        {
            parent::__construct();
            $dotenv = Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..');
            $dotenv->load();
        }

        public function isDownForMaintenance()
        {
            return is_file(static::APP_STORAGE_PATH . '/framework/down');
        }

        protected function getPath(string $var): string
        {
            $var = "APP_${var}_PATH";
            return constant("static::$var");
        }


        public function init(RequestInterface $request)
        {
            $this->kernel = new Kernel($this);

            $this->request = $request;
            $this->container->instance('CONFIG', $this->appConfig = $this->loadConfig("app"));

            $this->appConfig = $this->container->get('CONFIG');

            if ($request->method === 'OPTIONS') {
                $this->kernel->handleOPTIONS();
            }

            $this->bootstrap();


            // get and register REALM
            $REALM = RealmFinder::fromRequest($request);
            $request->setRealm($REALM);
            $this->container->instance('REALM', $REALM);
            $realmConfig = $this->loadConfig("$REALM/app");

            $this->response = $this->container->make('RESPONSE');

            $this->container->make(KernelInterface::class);


//            try {
            $this->execServiceProviders($this->appConfig['SERVICE_PROVIDERS'], $realmConfig['SERVICE_PROVIDERS']);

            // and run the middleware
            $this->kernel->execMiddleware($this->appConfig['MIDDLEWARE'], $realmConfig['MIDDLEWARE']);


            // get the Router Instance and search for a matching route
            /** @var RouteManagerInterface $routeManager */
            $routeManager = $this->container->get(RouteManagerInterface::class);
            $routeFile = $this->loadConfigFile(static::APP_APP_PATH . 'Routes' . DIRECTORY_SEPARATOR . $REALM);
            $match = $routeManager->matchRoute($routeFile);


            $this->kernel->checkForBoom($match);
            $this->kernel->execMiddleware($match->route->getMiddleware());
            $this->kernel->handleRoute($match);

            $this->prepareForShutdown();
            $this->shutDown();
        }


        protected function bootstrap()
        {
            //first things first...
            $this->container->instance(AppInterface::class, $this);
            $this->container->alias(AppInterface::class, App::class,);
            $this->container->alias(AppInterface::class, 'APP');

            $this->container->instance(RequestInterface::class, $this->request);
            $this->container->alias(RequestInterface::class, HttpRequest::class);
            $this->container->alias(RequestInterface::class, 'REQUEST');

            $this->container->singleton(ResponseInterface::class, HttpResponse::class);
            $this->container->alias(ResponseInterface::class, 'RESPONSE');

            $this->container->singleton(KernelInterface::class, Kernel::class);

            $this->container->instance(ContainerInterface::class, $this->container);

            $this->container->singleton(ExceptionHandler::class);

            $encryptor = new Encryptor($this->appConfig['app_key'], $this->appConfig['encryption_cipher']);
            $this->container->instance(EncryptorInterface::class, $encryptor);
        }
    }

