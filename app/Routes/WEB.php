<?php

    declare(strict_types=1);

    use Sourcegr\Framework\Http\Router\Route;
    use Sourcegr\Framework\Http\Router\RouteCollection;
    use Sourcegr\Framework\Http\Router\RouteMatch;

    return function(RouteCollection $routeCollection) {
        $routeCollection->setPrefix('prefix/', function(RouteCollection $routeCollection) {
            $routeCollection->setMiddleware('GLOBAL_MINE', function(RouteCollection $routeCollection) {
//                $routeCollection->GET('contact', function () {echo 'GET Contact';});
//                $routeCollection->GET('contact/1', function () {echo 'GET contact/1';});
//                $routeCollection->GET('contact/2', function () {echo 'GET contact/2';});
//                $routeCollection->GET('contact/3', function () {echo 'GET contact/3';});
//                $routeCollection->GET('contact/3/edit', function () {echo 'GET contact/3/edit';});
//                $routeCollection->GET('contact/#id', function () {echo 'GET contact/#id';})->where('id', '/^7$/');
//                $routeCollection->GET('contact/#id/#action', function () {echo 'GET contact/#id/#action';});


                $routeCollection->GET('#foo/#controller/?action', function () {
                    echo 'GET contact/--------';
//                })->matchesAll();
                })->setPredicate(function(RouteMatch $routeMatch) {
                    $routeMatch->varsMap['user'] = 'I am the user!';
                    return true;
                });

//                $routeCollection->GET('all', function () {
//                    echo 'GET Contact';
//                })->setPredicate(function($params) {
//                    $id = $params['id'];
//                    echo $id;
//                });
            });
        });
    };