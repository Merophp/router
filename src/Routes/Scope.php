<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

/**
 * A bracket for routes.
 */
class Scope
{

	/**
	 * @var string
	 */
	protected string $pattern = '';

	/**
	 * @var array
	 */
	protected array $entries = [];

    /**
     * @param $pattern
     * @param $entries
     */
	public function __construct($pattern, $entries)
    {
		$this->pattern = $pattern;
		$this->entries = $entries;
	}

	/**
	 * @return string
	 */
	public function getPattern(): string
    {
		return $this->pattern;
	}

	/**
	 * @param string $pattern
	 */
	public function setPattern(string $pattern)
    {
		$this->pattern = $pattern;
	}

	/**
	 * @return array
	 */
	public function getEntries(): array
    {
		return $this->entries;
	}
}
