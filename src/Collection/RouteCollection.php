<?php
declare(strict_types=1);

namespace Merophp\Router\Collection;

use ArrayIterator;
use Exception;
use IteratorAggregate;

use Merophp\Router\Exception\InvalidArgumentException;
use Merophp\Router\Routes\RouteInterface;
use Merophp\Router\Routes\Scope;
use Traversable;

/**
 * Route collection stores routes.
 */
class RouteCollection implements IteratorAggregate
{

    /**
     * @var array
     */
    public array $routes = [];

    /**
     * @param RouteInterface $route
     */
    public function add(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    /**
     * @param iterable $routes
     */
    public function addMultiple(iterable $routes)
    {
        foreach($routes as $route){
            $this->add($route);
        }
    }

    /**
     * @param Scope $scope
     * @throws InvalidArgumentException
     */
    public function addFromScope(Scope $scope)
    {
        $this->solveScope($scope);
    }

    /**
     * Translate a scope to final route definitions.
     *
     * @param Scope $scope
     * @throws InvalidArgumentException
     */
    protected function solveScope(Scope $scope)
    {
        $pattern = $scope->getPattern();
        $entries = $scope->getEntries();

        foreach($entries as $entry){

            if($entry instanceof RouteInterface){
                $entry->setPattern($pattern.$entry->getPattern());
                $this->add($entry);
            }
            else if($entry instanceof Scope){
                $entry->setPattern($pattern.$entry->getPattern());
                $this->solveScope($entry);
            }
            else{
                throw new InvalidArgumentException('$entry is neither a Route nor a Scope!');
            }
        }
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !(bool)count($this->routes);
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->routes);
    }
}
