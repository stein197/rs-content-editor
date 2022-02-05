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

	private array $routes = [];
	private array $middleware = [];
	private string $groupPrefix = '';

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
		$this->middleware = array_merge($this->middleware, $middleware);
		return $this;
	}

	public function after(string | callable ...$middleware): void {
		foreach ($this->routes as &$route)
			$route['handler'] = array_merge($route['handler'], $middleware);
		$this->middleware = $this->middleware ? [] : $this->middleware;
	}

	public function any(string $route, string | callable $handler): self {
		$this->match(HTTP_METHODS, $route, $handler);
		return $this;
	}

	public function match(array $methods, string $route, string | callable $handler): self {
		$route = $this->groupPrefix.$route;
		foreach ($methods as $method)
			$this->routes[] = [
				'method' => $method,
				'route' => $route,
				'handler' => array_merge($this->middleware, [$handler])
			];
		$this->middleware = [];
		return $this;
	}

	public function group(string $prefix, callable $callback): self {
		$prevPrefix = $this->groupPrefix;
		$this->groupPrefix = $prevPrefix.$prefix;
		$callback($this);
		$this->groupPrefix = $prevPrefix;
		return $this;
	}

	public function getRoutes(): array {
		return $this->routes;
	}
}
