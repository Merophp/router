<?php

use PHPUnit\Framework\TestCase;

use Merophp\Router\{
    Collection\RouteCollection,
    Routes\GetRoute,
};

use Merophp\Router\Provider\{
    CompoundRouteProvider,
    RouteProvider
};

/**
 * @covers \Merophp\Router\Provider\CompoundRouteProvider
 */
final class CompositeRouteProviderTest extends TestCase
{

    /**
     * @doesNotPerformAssertions
     * @return CompoundRouteProvider
     */
    public function testAttach(): CompoundRouteProvider
    {
        $routeCollectionA = new RouteCollection;
        $routeCollectionA->addMultiple([
            new GetRoute('/api/v1/1', function(){}),
            new GetRoute('/api/v1/2', function(){}),
        ]);

        $routeCollectionB = new RouteCollection;
        $routeCollectionB->addMultiple([
            new GetRoute('/api/v1/3', function(){}),
            new GetRoute('/api/v1/4', function(){}),
        ]);

        $compositeRouteProvider = new CompoundRouteProvider;
        $compositeRouteProvider->attach(new RouteProvider($routeCollectionA));
        $compositeRouteProvider->attach(new RouteProvider($routeCollectionB));

        return $compositeRouteProvider;
    }

    /**
     * @depends testAttach
     */
    public function testGetRoutesForHttpMethod(CompoundRouteProvider $compositeRouteProvider){
        $this->assertCount(4, iterator_to_array($compositeRouteProvider->getRoutesForHttpMethod('GET'), false));
    }

}
