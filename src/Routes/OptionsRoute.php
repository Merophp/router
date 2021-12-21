<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * Route only for OPTIONS-Requests.
 */
final class OptionsRoute extends Route
{
	public function __construct($pattern, $handler)
    {
		parent::__construct(['OPTIONS'], $pattern, $handler);
	}
}
