<?php
namespace App\Routing;

use BadMethodCallException;
use const App\HTTP_METHODS;

/**
 * @method self get(string $route, string | callable $handler)
 * @method self head(string $route, string | callable $handler)
 * @method self post(string $route, string | callable $handler)
 * @method self put(string $route, string | callable $handler)
 * @method self delete(string $route, string | callable $handler)
 * @method self connect(string $route, string | callable $handler)
 * @method self options(string $route, string | callable $handler)
 * @method self trace(string $route, string | callable $handler)
 * @method self patch(string $route, string | callable $handler)
 */
final class Builder {

	private const MIDDLEWARE_BEFORE = 'before';
	private const MIDDLEWARE_AFTER = 'after';
	private const MIDDLEWARE_WITHOUT = 'without';

	private array $middleware = [];
	private array $config = [];
	private array $curEntry;

	public function __construct(callable $callback) {
		$callback($this);
	}

	public function __call(string $name, array $args): self {
		$name = strtoupper($name);
		if (!in_array($name, HTTP_METHODS))
			throw new BadMethodCallException('Call to undefined method '.__CLASS__."::{$name}");
		$this->match([$name], $args[0], $args[1]);
		return $this;
	}

	public function before(string | callable ...$middleware): self {
		$this->middleware[self::MIDDLEWARE_AFTER] = $this->middleware[self::MIDDLEWARE_WITHOUT] = null;
		return $this->addMiddleware(self::MIDDLEWARE_BEFORE, false, ...$middleware);
	}

	public function after(string | callable ...$middleware): self {
		$this->middleware[self::MIDDLEWARE_BEFORE] = null;
		$this->addMiddleware(self::MIDDLEWARE_AFTER, true, ...$middleware);
		return $this;
	}

	public function without(string | callable ...$middleware): self {
		$this->middleware[self::MIDDLEWARE_BEFORE] = null;
		$this->addMiddleware(self::MIDDLEWARE_WITHOUT, true, ...$middleware);
		return $this;
	}

	private function addMiddleware(string $key, bool $assign, string | callable ...$middleware): self {
		$this->middleware[$key] = @$this->middleware[$key] ? array_merge($this->middleware[$key], $middleware) : $middleware;
		if ($assign) {
			$this->curEntry[$key] = $this->middleware[$key];
			$this->middleware[$key] = [];
		}
		return $this;
	}

	public function any(string $route, string | callable $handler): self {
		$this->match(HTTP_METHODS, $route, $handler);
		return $this;
	}

	public function match(array $methods, string $route, string | callable $handler): self {
		foreach ($methods as $method) {
			$this->config[] = [
				'method' => $method,
				'route' => $route,
				'handler' => $handler
			];
			$this->curEntry = &$this->config[sizeof($this->config) - 1];
			$this->assignMiddleware(self::MIDDLEWARE_BEFORE, $this->curEntry);
			$this->middleware = [];
		}
		return $this;
	}

	public function group(string $prefix, callable $callback): self {
		$this->config[] = [
			'prefix' => $prefix
		];
		$this->curEntry = &$this->config[sizeof($this->config) - 1];
		$this->assignMiddleware(self::MIDDLEWARE_BEFORE, $this->curEntry);
		$this->middleware = [];
		$this->curEntry['group'] = (new self($callback))->config;
		return $this;
	}

	/**
	 * Возвращает линейный список маршрутов.
	 * @return RouteInfo[]
	 */
	public function getRoutes(): array {
		$result = [];
		self::flatConfig($this->config, $result);
		return $result;
	}

	private function assignMiddleware(string $key, array &$array): void {
		$array[$key] = @$this->middleware[$key] ?? [];
		$this->middleware[$key] = [];
	}

	private static function flatConfig($array, &$result, $prefix = '', $before = [], $after = [], $without = []) {
		foreach ($array as $item) {
			$curBefore = array_merge($before, @$item[self::MIDDLEWARE_BEFORE] ?? []);
			$curAfter = array_merge(@$item[self::MIDDLEWARE_AFTER] ?? [], $after);
			$curWithout = array_merge($without, @$item[self::MIDDLEWARE_WITHOUT] ?? []);
			if (@$item['group'] != null)
				self::flatConfig($item['group'], $result, $prefix.$item['prefix'], $curBefore, $curAfter, $curWithout);
			else
				$result[] = new RouteInfo(strtoupper($item['method']), preg_replace('/\/+/', '/', $prefix.$item['route']), array_values(array_diff(array_merge($curBefore, [$item['handler']], $curAfter), $curWithout)));
		}
	}
}
