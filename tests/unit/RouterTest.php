<?php

use PHPUnit\Framework\TestCase;

use Merophp\Router\{
    Router,
    Collection\RouteCollection,
    Exception\RouteNotFoundException,
    Exception\RoutingException,
    Routes\GetRoute,
    Routes\PostRoute,
    Routes\Route
};

use Merophp\Router\Provider\{
    CompoundRouteProvider,
    RouteProvider
};

/**
 * @covers \Merophp\Router\Dispatcher\RouteDispatcher
 */
final class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    protected static Router $routerInstance;


    public static function setUpBeforeClass(): void
    {
        $routeCollectionA = new RouteCollection;
        $routeCollectionA->addMultiple([
            new GetRoute('/api/v1/foo/*', function(){}),
            new GetRoute('/api/v1/bar', function(){}),
        ]);

        $routeCollectionB = new RouteCollection;
        $routeCollectionB->addMultiple([
            new GetRoute('/api/v1/foo/bar.php', function(){}),
            new GetRoute('/api/v1/foo/bar', function(){}),
            new GetRoute('/api/v1/bar/*', function(){}),
            new PostRoute('/api/v1/foo/*', function(){}),
            new GetRoute('/api/v1/foo/{arg1}/{arg_2}/hello', function(){}),
            new Route(['*'], '/api/v1/foo/bar/hello', function(){})
        ]);

        $compositeRouteProvider = new CompoundRouteProvider;
        $compositeRouteProvider->attach(new RouteProvider($routeCollectionA));
        $compositeRouteProvider->attach(new RouteProvider($routeCollectionB));

        self::$routerInstance = new Router(
            $compositeRouteProvider
        );
    }

    public function testMatch()
    {
        $route = self::$routerInstance->match('GET', '/api/v1/foo/Ano/Nymous/hello');
        $this->assertEquals('/api/v1/foo/{arg1}/{arg_2}/hello', $route->getPattern());
        $this->assertEquals('Ano', $route->getArguments()[0]);
        $this->assertEquals('Nymous', $route->getArguments()[1]);

        $route = self::$routerInstance->match('GET', '/api/v1/foo/Foo+Bar%._-/Nymous/hello');
        $this->assertEquals('/api/v1/foo/{arg1}/{arg_2}/hello', $route->getPattern());
        $this->assertEquals('Foo+Bar%._-', $route->getArguments()[0]);
        $this->assertEquals('Nymous', $route->getArguments()[1]);

        $route = self::$routerInstance->match('GET', '/api/v1/foo/Ano/Nymous/by');
        $this->assertEquals('/api/v1/foo/*', $route->getPattern());
        $this->assertContains('GET', $route->getMethods());

        $route = self::$routerInstance->match('POST', '/api/v1/foo/Ano/Nymous/by');
        $this->assertEquals('/api/v1/foo/*', $route->getPattern());
        $this->assertContains('POST', $route->getMethods());

        $route = self::$routerInstance->match('PUT', '/api/v1/foo/bar/hello');
        $this->assertEquals('/api/v1/foo/bar/hello', $route->getPattern());
        $this->assertContains('*', $route->getMethods());

        $route = self::$routerInstance->match('GET', '/api/v1/foo/bar.php');
        $this->assertEquals('/api/v1/foo/bar.php', $route->getPattern());
    }

    public function testExpectNotFoundException()
    {
        $this->expectException(RouteNotFoundException::class);
        self::$routerInstance->match('GET', '');
    }

    public function testDispatch()
    {
        $returnValue = self::$routerInstance->dispatch(
            new GetRoute('/api/v1/1', function(){
                return 'Test';
            }),
        );
        $this->assertEquals('Test', $returnValue);
    }

    public function testDispatchWithInvalidHandler()
    {
        $this->expectException(RoutingException::class);
        self::$routerInstance->dispatch(
            new GetRoute('/api/v1/1', null),
        );
    }

    public function testDispatchWithAdditionalArguments()
    {
        $route =  new GetRoute('/api/v1/1', function($a, $b, $c, $d){
            return $a.$b.$c.$d;
        });
        $route->setArguments(['Foo','Bar']);

        $returnValue = self::$routerInstance->dispatch($route, ['Test','Me']);

        $this->assertEquals('TestMeFooBar', $returnValue);
    }

}
