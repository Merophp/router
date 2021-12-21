<?php

use PHPUnit\Framework\TestCase;

use Merophp\Router\{
    Collection\RouteCollection,
    Routes\GetRoute,
    Routes\PostRoute,
    Routes\Route,
};

use Merophp\Router\Provider\RouteProvider;

/**
 * @covers \Merophp\Router\Provider\RouteProvider
 */
final class RouteProviderTest extends TestCase
{
    public function testGetRoutesForHttpMethod(){
        $routeCollection = new RouteCollection;
        $routeCollection->addMultiple([
            new GetRoute('/api/v1/foo/*', function(){}),
            new PostRoute('/api/v1/bar', function(){}),
            new Route(['*'],'/api/v1/bar', function(){}),
            new Route(['GET', 'PUT'],'/api/v1/bar', function(){}),
        ]);

        $routeProvider = new RouteProvider($routeCollection);
        $this->assertCount(2, iterator_to_array($routeProvider->getRoutesForHttpMethod('PUT')));
        $this->assertCount(3, iterator_to_array($routeProvider->getRoutesForHttpMethod('GET')));
    }
}
