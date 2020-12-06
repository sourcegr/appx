<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\Hashing\HashingManager;
    use Sourcegr\Framework\Base\Hashing\HashingManagerInterface;
    use Sourcegr\Framework\Base\ServiceProvider;


    class HashingServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $config = $this->loadConfig('hashing');

            $manager = new HashingManager();

            $this->container->instance(HashingManagerInterface::class, $manager);

            // lazy register all providers to the container
            foreach ($config['providers'] as $hasherName => $hasherConfig) {
                $this->container->singleton(
                    'HashProviders.' . $hasherName,
                    function () use ($manager, $hasherName, $hasherConfig) {
                        return $manager->createHasher($hasherName, $hasherConfig);
                    }
                );

                $default = $config['default'] ?? null;

                if (!$default || !$this->container->has('HashProviders.' . $default)) {
                    throw new \Exception('HashingServiceProvider: The default hash engine does not exist');
                }

                $this->container->alias('HashProviders.' . $default, 'HashProviders.default');
            }
        }

        public function boot()
        {
        }
    }