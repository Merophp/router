<?php
declare(strict_types=1);

namespace Merophp\Router\Provider;

use Merophp\Router\Collection\RouteCollection;

final class RouteProvider implements RouteProviderInterface{

    /**
     * @var RouteCollection
     */
    private RouteCollection $routeCollection;

    /**
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getRoutesForHttpMethod(string $httpMethod): iterable
    {
        yield from array_filter(iterator_to_array($this->routeCollection), function($route) use ($httpMethod){
            return
                in_array('*', $route->getMethods())
                ||  in_array(strtoupper($httpMethod), $route->getMethods());
        });
    }

    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getSortedRoutesForHttpMethod(string $httpMethod): iterable
    {
        $routes = iterator_to_array($this->getRoutesForHttpMethod($httpMethod));
        usort($routes, function($b, $a){
            return (substr_count($a->getPattern(),'/') == substr_count($b->getPattern(),'/') ? strcmp($a->getPattern(), $b->getPattern()) : substr_count($a->getPattern(),'/') - substr_count($b->getPattern(),'/'));
        });
        yield from $routes;
    }
}
