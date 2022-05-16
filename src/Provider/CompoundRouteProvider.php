<?php
declare(strict_types=1);

namespace Merophp\Router\Provider;

use Merophp\Router\Utility\RouterUtility;

/**
 * CompoundRouteProvider is a route provider that allows combining multiple route providers.
 */
final class CompoundRouteProvider implements RouteProviderInterface{

    /**
     * @var RouteProviderInterface[]
     */
    private array $providers = [];

    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getRoutesForHttpMethod(string $httpMethod): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getRoutesForHttpMethod($httpMethod);
        }
    }

    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getSortedRoutesForHttpMethod(string $httpMethod): iterable
    {
        $routes = iterator_to_array($this->getRoutesForHttpMethod($httpMethod), false);
		RouterUtility::sortRoutesByPriority($routes);
        yield from $routes;
    }

    /**
     * Adds provider as a source for routes.
     *
     * @param RouteProviderInterface $provider
     */
    public function attach(RouteProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}
