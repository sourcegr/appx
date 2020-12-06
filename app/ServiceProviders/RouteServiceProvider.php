<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use App\ContainerAwarePredicateCompiler;
    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Http\Router\PredicateCompilerInterface;
    use Sourcegr\Framework\Http\Router\RouteManagerInterface;
    use Sourcegr\Framework\Http\Router\RouteManager;


    class RouteServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->container->bind(PredicateCompilerInterface::class, ContainerAwarePredicateCompiler::class);
//            $this->container->bind(RouteMatchInterface::class, RouteMatch::class);

            $this->container->singleton(
                RouteManagerInterface::class,
                RouteManager::class
            );
        }

        public function boot()
        {
        }
    }