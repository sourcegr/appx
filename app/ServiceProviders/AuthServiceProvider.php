<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\Auth\AuthUserProviderManager;
    use Sourcegr\Framework\Base\Auth\AuthUserProviderManagerInterface;
    use Sourcegr\Framework\Base\Auth\Guard\SessionGuard;
    use Sourcegr\Framework\Base\Auth\Guard\TokenGuard;
    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Http\Request\RequestInterface;


    class AuthServiceProvider extends ServiceProvider
    {
        protected $config = null;
        protected $manager = null;

        public function register()
        {
            $config = $this->loadConfig('auth');
            $realm = $this->container->get('REALM');

            $allProviders = $config['REALM'][$realm];

            $manager = new AuthUserProviderManager();

            $this->container->instance(AuthUserProviderManagerInterface::class, $manager);

            // register all providers to the container
            foreach ($allProviders as $providerName => $implementation) {
                $providerConfig = $config['providers'][$implementation['provider']];
                $guardConfig = $implementation['driver_params'] ?? [];

                switch ($implementation['driver']) {
                    case 'session':
                        $guardClass = SessionGuard::class;
                        break;

                    case 'token':
                        $guardClass = TokenGuard::class;
                        break;

                    default:
                        throw new \Exception('AuthServiceProvider: No driver configured');
                }

                $this->container->singleton(
                    'AuthUserProviders.' . $providerName,
                    function ($container) use ($providerName, $providerConfig, $guardClass, $guardConfig) {
                        $guard = $this->container->make($guardClass, ['config' => $guardConfig]);
                        return $this->setUpProvider($providerName, $providerConfig, $guard);
                    }
                );

            }

            $this->config = $config;
            $this->manager = $manager;
        }


        public function boot(RequestInterface $r)
        {
            $realm = $this->container->get('REALM');
            $default = $this->config['default'][$realm] ?? null;


            if (!$default || !$this->container->has('AuthUserProviders.' . $default)) {
                throw new \Exception('AuthServiceProvider: The default engine does not exist');
            }

            $r->auth = $this->container->get('AuthUserProviders.' . $default);
            $this->container->alias('AuthUserProviders.' . $default, 'AuthUserProvider');
        }


        protected function setUpProvider($providerName, $providerConfig, $guard)
        {
            switch ($providerConfig['engine']) {
                case 'DB':
                    $connectionName = $providerConfig['connection'] ?? 'default';

                    $connection = $this->container->get('DB.connections.' . $connectionName);
                    $db = new DB($connection);

                    $hasherName = $providerConfig['hasher'] ?? 'default';
                    $hasher = $this->container->get('HashProviders.' . $hasherName);

                    $provider = $this->manager->createProvider($providerName, $providerConfig);
                    $provider->setQueryBuilder($db);
                    $provider->setHasher($hasher);

                    $guard->setProvider($provider);
                    break;

                default:
                    // todo: Add check to see if user provided a ClassName to instantiate...
                    // or else...
                    throw new \Exception('AuthServiceProvider: Unknown engine ' . $providerConfig['engine']);
            }
            return $guard;
        }
    }