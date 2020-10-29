<?php

    declare(strict_types=1);


    namespace App;


    use Sourcegr\Framework\Http\Router\PredicateCompilerInterface;
    use Sourcegr\Framework\Http\Router\RouteMatchInterface;

    class ContainerAwarePredicateCompiler implements PredicateCompilerInterface
    {

        public function runPredicate($callback, RouteMatchInterface $routeMatch)
        {
            return app()->container->call($callback, [
                'routeMatch' => $routeMatch
            ]);
        }
    }