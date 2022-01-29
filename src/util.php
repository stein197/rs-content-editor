<?php
namespace App;

use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;

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
			$className = "App\\Controller\\{$routeInfo[1]}";
			if (class_exists($className)) {
				$controller = new $className($requestMethod, $requestUri, $routeInfo[2]);
				return $controller->{strtolower($requestMethod)}();
			} else {
				return "Controller class \"{$className}\" does not exist";
			}
	}
}
