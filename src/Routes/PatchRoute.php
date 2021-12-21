<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for PATCH-Requests.
 */
final class PatchRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['PATCH'], $pattern, $handler);
	}
}
