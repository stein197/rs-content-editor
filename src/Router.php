<?php

namespace App;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Router {

	public function __construct(private RouteBuilder $routeBuilder) {}

	public function dispatch(string $requestMethod, string $requestUri): RouteHandler {
		$dispatcher = simpleDispatcher(function (RouteCollector $r): void {
			foreach ($this->routeBuilder->getRoutes() as $route)
				$r->addRoute($route['method'], $route['route'], $route['handler']);
		});
		return new RouteHandler($requestMethod, $requestUri, $dispatcher->dispatch($requestMethod, $requestUri));
	}
}
