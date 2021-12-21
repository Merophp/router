<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for DELETE-Requests.
 */
final class DeleteRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['DELETE'], $pattern, $handler);
	}
}
