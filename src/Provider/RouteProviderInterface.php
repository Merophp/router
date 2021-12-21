<?php
declare(strict_types=1);

namespace Merophp\Router\Provider;

interface RouteProviderInterface{
    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getRoutesForHttpMethod(string $httpMethod):iterable;

    /**
     * @param string $httpMethod
     * @return iterable
     */
    public function getSortedRoutesForHttpMethod(string $httpMethod):iterable;
}
