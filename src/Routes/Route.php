<?php
declare(strict_types=1);

namespace Merophp\Router\Routes;

use Closure;

/**
 * @author Robert Becker
 */
class Route implements RouteInterface
{

	/**
	 * @var string
	 */
	protected string $pattern = '';

	/**
	 * @var mixed
	 */
	protected $handler;

	/**
	 * @var array
	 */
	protected array $methods = [];

    /**
     * @var array
     */
    protected array $arguments = [];

	/**
	 * @param array $methods
	 * @param string $pattern
	 * @param mixed $handler
	 */
	public function __construct(array $methods, string $pattern, $handler)
    {
		$this->methods = $methods;
		$this->pattern = $pattern;
        $this->setHandler($handler);
	}

	/**
	 * @return array
	 */
	public function getMethods(): array
    {
		return $this->methods;
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
	public function setPattern(string $pattern): void
    {
		$this->pattern = $pattern;
	}

	/**
	 * @return mixed
	 */
	public function getHandler()
    {
		return $this->handler;
	}

    /**
     * @param mixed $handler
     */
	public function setHandler($handler): void
    {
		$this->handler = $handler;
	}

    /**
     * @array array
     */
	public function setArguments($arguments): void
    {
		$this->arguments = $arguments;
	}

    /**
     * @return array
     */
	public function getArguments(): array
    {
		return $this->arguments;
	}

    /**
     * Returns the HTTP methods, the pattern and the handler of a route as a string.
     * @return string
     */
	public function __toString(): string
    {
		return sprintf(
			'[%s] %s : %s',
			implode(',', $this->getMethods()),
			$this->getPattern(),
			$this->getCallableName($this->getHandler())
		);
	}

	/**
     * @param mixed $callable
	 * @return string The name of the callable
	 */
	private function getCallableName($callable): string
    {
		if (is_string($callable)) {
			return trim($callable);
		} else if (is_array($callable)) {
			if (is_object($callable[0])) {
				return sprintf("%s::%s", get_class($callable[0]), trim($callable[1]));
			} else {
				return sprintf("%s::%s", trim($callable[0]), trim($callable[1]));
			}
		} else if ($callable instanceof Closure) {
			return 'closure';
		}
		return 'unknown';
	}
}
