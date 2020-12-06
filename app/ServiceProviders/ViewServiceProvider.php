<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Base\View\ViewManager;


    class ViewServiceProvider extends ServiceProvider
    {

        public function register()
        {
            $config = $this->loadConfig('view');
            $this->container->singleton(
                ViewManager::class,
                function () use ($config) {
                    $vm = new ViewManager($config['views'], $config['cache']);

                    foreach ($config['namespaces'] as $ns => $dir) {
                        $vm->addNamespace($ns, $dir);
                    }

                    foreach ($config['globals'] as $name => $value) {
                        $vm->addGlobal($name, $value);
                    }

                    return $vm;
                }
            );
        }


        public function boot()
        {
        }
    }