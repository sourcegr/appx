<?php

    declare(strict_types=1);

    use App\Http\Controllers\FreeTicketsController;
    use App\Http\Controllers\ScheduledTaskController;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Router\RestfullRoute;
    use Sourcegr\Framework\Http\Router\RouteCollection;


    return function (RouteCollection $routeCollection) {
        $routeCollection->setPrefix('api', function (RouteCollection $routeCollection) {
            $routeCollection->setPrefix('scheduled-task', function (RouteCollection $routeCollection) {
                $routeCollection->rest('task', ScheduledTaskController::class, function(RestfullRoute $route) {
                    $route->setVarName('task_id');
                    $route->excludeRelationMethod('put');
                    $route->setPredicate(function(RequestInterface $r) {
                        $_ENV['created_by'] = $r->user['id'];
                    });
                });
            });

            $routeCollection->rest('tickets', FreeTicketsController::class, function (RestfullRoute $route) {
                $route->setVarName('batchId');
                $route->add('publish', 'GET');
                $route->add('addTickets', 'PATCH');

                $route->rest('company', FreeTicketsController::class, function (RestfullRoute $route) {
                    $route->setVarName('companyId');
                    $route->excludeMethod('put');

                    $route->addRelation('person')
                        ->setVarName('personId');
                });
            });
        });
    };