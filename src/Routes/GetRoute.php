<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for GET-Requests.
 */
final class GetRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['GET'], $pattern, $handler);
	}
}
