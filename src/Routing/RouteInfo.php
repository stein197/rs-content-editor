<?php
namespace App\Routing;

final class RouteInfo {

	public function __construct(public readonly string $method, public readonly string $route, public readonly array $handlers) {}
}
