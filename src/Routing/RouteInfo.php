<?php
namespace App\Routing;

final class RouteInfo {

	public function __construct(private string $method, private string $route, private array $handlers, private ?string $name) {}

	public function getMethod(): string {
		return $this->method;
	}

	public function getRoute(): string {
		return $this->route;
	}

	public function getHandlers(): array {
		return $this->handlers;
	}

	public function getName(): ?string {
		return $this->name;
	}
}
