<?php
declare(strict_types=1);

namespace Merophp\Router;

use Merophp\Router\Exception\RoutingException;
use Merophp\Router\Routes\RouteInterface;

use Merophp\Router\Provider\RouteProviderInterface;

use Merophp\Router\Exception\RouteNotFoundException;

final class Router{

    /**
     * @var RouteProviderInterface
     */
    private RouteProviderInterface $routeProvider;

    /**
     * @param RouteProviderInterface $routeProvider
     */
    public function __construct(RouteProviderInterface $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * @param string $httpMethod
     * @param string $uriPath
     * @return RouteInterface
     * @throws RouteNotFoundException
     */
	public function match(string $httpMethod, string $uriPath): RouteInterface
    {
        /*
         * Search matching route
         */
        foreach($this->routeProvider->getSortedRoutesForHttpMethod($httpMethod) as $route){
            $pregPattern = '#^'.preg_replace(
                ['#\\.#', '#\\{[A-Za-z0-9]+\\}#','#\\*#'],
                ['\\.', '([A-Za-z0-9\\_\\-]+)','.*'],
                $route->getPattern()
            ).'$#';
            if(preg_match($pregPattern, $uriPath, $matches)){
                array_shift($matches);
                $route->setArguments($matches);

                return $route;
            }
        }

        throw new RouteNotFoundException(sprintf(
            'No route found for uri path "%s" and HTTP method "%s"',
            $uriPath,
            $httpMethod
        ));
	}

    /**
     * @param RouteInterface $route
     * @param array $arguments
     * @return false|mixed
     * @throws RoutingException
     */
    public function dispatch(RouteInterface $route, array $arguments = [])
    {
		$callable = $route->getHandler();

        if(!is_callable($callable, true))
            throw new RoutingException('Route handler is not callable!');

        return call_user_func_array(
			$callable,
			array_merge($arguments, $route->getArguments())
		);
    }
}
