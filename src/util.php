<?php
namespace App;

use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;

function init(): void {
	header('Content-Type: application/json');
}

function container(): Container {
	static $container;
	if (!$container) {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'definition.php');
		$builder->useAutowiring(false);
		$container = $builder->build();
	}
	return $container;
}

function handleRoute(string $requestMethod, string $requestUri, array $routeInfo): array {
	switch ($routeInfo[0]) {
		case Dispatcher::NOT_FOUND:
		case Dispatcher::METHOD_NOT_ALLOWED:
			return 'Dispatcher error: NOT_FOUND or METHOD_NOT_ALLOWED';
		case Dispatcher::FOUND:
			return (new $routeInfo[1]($requestMethod, $requestUri, $routeInfo[2]))->{strtolower($requestMethod)}();
	}
}
