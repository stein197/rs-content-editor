<?php

namespace App;

use Exception;
use FastRoute\Dispatcher;

class RouteHandler {

	public function __construct(private string $requestMethod, private string $requestUri, private $routeInfo) {}

	public function handle(): void {
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
			case Dispatcher::METHOD_NOT_ALLOWED:
				throw new Exception('Dispatcher error: NOT_FOUND or METHOD_NOT_ALLOWED');
			case Dispatcher::FOUND:
				foreach ($this->routeInfo[1] as $handler) {
					if (is_callable($handler)) {
						$handler($this->requestMethod, $this->requestUri, $this->routeInfo[2]);
					} elseif (is_string($handler)) {
						if (class_exists($handler))
							(new $handler())->handle($this->requestMethod, $this->requestUri, $this->routeInfo[2]);
						else
							throw new Exception("Class \"{$handler}\" not found", 500);
					} else {
						throw new Exception("Unknown handler {$handler}");
					}
				}
		}
	}
}
