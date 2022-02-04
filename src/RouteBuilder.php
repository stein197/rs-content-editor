<?php

namespace App;

final class RouteBuilder {

	private array $routes = [];
	private array | null $middleware = null;
	private string $groupPrefix = '';

	public function __construct(callable $callback) {
		$callback($this);
	}

	public function before(string ...$middleware): self {
		$this->middleware = $middleware;
		return $this;
	}

	public function get(string $route, string | callable $handler): void {
		$this->match(['GET'], $route, $handler);
	}

	public function head(string $route, string | callable $handler): void {
		$this->match(['HEAD'], $route, $handler);
	}

	public function post(string $route, string | callable $handler): void {
		$this->match(['POST'], $route, $handler);
	}

	public function put(string $route, string | callable $handler): void {
		$this->match(['PUT'], $route, $handler);
	}

	public function delete(string $route, string | callable $handler): void {
		$this->match(['DELETE'], $route, $handler);
	}

	public function connect(string $route, string | callable $handler): void {
		$this->match(['CONNECT'], $route, $handler);
	}

	public function options(string $route, string | callable $handler): void {
		$this->match(['OPTIONS'], $route, $handler);
	}

	public function trace(string $route, string | callable $handler): void {
		$this->match(['TRACE'], $route, $handler);
	}

	public function patch(string $route, string | callable $handler): void {
		$this->match(['PATCH'], $route, $handler);
	}

	public function any(string $route, string | callable $handler): void {
		$this->match(HTTP_METHODS, $route, $handler);
	}

	public function match(array $methods, string $route, string | callable $handler): void {
		$route = $this->groupPrefix.$route;
		foreach ($methods as $method)
			$this->routes[] = [
				'method' => $method,
				'route' => $route,
				'handler' => array_merge($this->middleware, [$handler])
			];
		$this->middleware = null;
	}

	public function group(string $prefix, callable $callback): void {
		$prevPrefix = $this->groupPrefix;
		$this->groupPrefix = $prevPrefix.$prefix;
		$callback($this);
		$this->groupPrefix = $prevPrefix;
	}

	public function getRoutes(): array {
		return $this->routes;
	}
}
