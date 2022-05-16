<?php
namespace Merophp\Router\Utility;

use Merophp\Router\Routes\RouteInterface;

class RouterUtility
{
	/**
	 * @param RouteInterface[] $routes
	 */
	public static function sortRoutesByPriority(array &$routes)
	{
		usort($routes, function($a, $b){
			if(substr_count($b->getPattern(),'/') != substr_count($a->getPattern(),'/'))
				return substr_count($b->getPattern(),'/') - substr_count($a->getPattern(),'/');

			foreach(['*','{'] as $specialChar){
				if(
					strpos($a->getPattern(), $specialChar) !== false && strpos($b->getPattern(), $specialChar) === false
				){
					return 1;
				}
				elseif(
					strpos($a->getPattern(), $specialChar) === false && strpos($b->getPattern(), $specialChar) !== false
				){
					return -1;
				}
			}

			return strcmp($a->getPattern(), $b->getPattern());
		});
	}
}
