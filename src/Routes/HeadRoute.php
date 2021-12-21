<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for HEAD-Requests.
 */
final class HeadRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['HEAD'], $pattern, $handler);
	}
}
