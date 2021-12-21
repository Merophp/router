<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for POST-Requests.
 */
final class PostRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['POST'], $pattern, $handler);
	}
}
