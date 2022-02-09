<?php

namespace App\Routing;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Router {

	/** @var RouteInfo[] */
	private array $routes;

	public function __construct(Builder $routeBuilder) {
		$this->routes = $routeBuilder->getRoutes();
	}

	public function dispatch(string $requestMethod, string $requestUri): Handler {
		$dispatcher = simpleDispatcher(function (RouteCollector $r): void {
			foreach ($this->routes as $route)
				$r->addRoute($route->getMethod(), $route->getRoute(), $route->getHandlers());
		});
		return new Handler($dispatcher->dispatch($requestMethod, $requestUri));
	}

	public function getRouteByName(string $name): ?RouteInfo {
		foreach ($this->routes as $route)
			if ($route->getName() === $name)
				return $route;
		return null;
	}
}
