<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for PUT-Requests.
 */
final class PutRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['PUT'], $pattern, $handler);
	}
}
