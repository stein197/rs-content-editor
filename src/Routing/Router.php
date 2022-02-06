<?php

namespace App\Routing;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Router {

	public function __construct(private Builder $routeBuilder) {}

	public function dispatch(string $requestMethod, string $requestUri): Handler {
		$dispatcher = simpleDispatcher(function (RouteCollector $r): void {
			foreach ($this->routeBuilder->getRoutes() as $route)
				$r->addRoute($route->method, $route->route, $route->handlers);
		});
		return new Handler($requestMethod, $requestUri, $dispatcher->dispatch($requestMethod, $requestUri));
	}
}
