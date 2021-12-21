<?php

use PHPUnit\Framework\TestCase;

use Merophp\Router\{
    Collection\RouteCollection,
    Routes\GetRoute,
    Routes\PostRoute,
    Routes\Scope,
    Exception\InvalidArgumentException
};

/**
 * @covers \Merophp\Router\Collection\RouteCollection
 */
final class RouteCollectionTest extends TestCase
{
    public function testAddMultiple(){
        $collection = new RouteCollection;
        $this->assertTrue($collection->isEmpty());
        $collection->addMultiple([
            new GetRoute('/test', function(){}),
            new PostRoute('/test', function(){}),
        ]);
        $this->assertFalse($collection->isEmpty());
    }

    public function testAddMultipleWithInvalidArgument(){
        $this->expectError();
        $collection = new RouteCollection;
        $collection->addMultiple(
            'test'
        );
    }

    public function testAddWithInvalidArgument(){
        $this->expectError();
        $collection = new RouteCollection;
        $collection->add(
            'test'
        );
    }

    /**
     * @return RouteCollection
     * @doesNotPerformAssertions
     */
    public function testAddFromScope(){
        $collection = new RouteCollection;
        $collection->addFromScope(
            new Scope(
                '/api',
                [
                    new GetRoute('/test', function(){}),
                    new Scope(
                        '/v1',
                        [
                            new PostRoute('/test', function(){}),
                        ]
                    )
                ]
            )
        );

        return $collection;
    }

    public function testAddFromScopeWithInvalidArgument(){
        $this->expectException(InvalidArgumentException::class);
        $collection = new RouteCollection;
        $collection->addFromScope(
            new Scope(
            '/api',
                [
                    new GetRoute('/test', function(){}),
                    null
                ]
            )
        );
    }

    /**
     * @depends testAddFromScope
     */
    public function testGetForHttpMethod(RouteCollection $collection)
    {
        $routes = iterator_to_array($collection);
        $this->assertCount(2, $routes);
        $this->assertEquals('/api/test', $routes[0]->getPattern());
        $this->assertEquals('/api/v1/test', $routes[1]->getPattern());
    }

}
