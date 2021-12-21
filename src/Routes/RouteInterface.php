<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

interface RouteInterface
{
	public function getMethods(): array;
	public function getPattern(): string;
	public function setPattern(string $pattern): void;
	public function getHandler();
}
