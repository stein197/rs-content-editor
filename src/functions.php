<?php
namespace App;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use FastRoute\Dispatcher;

function container(): Container {
	static $container;
	if (!$container) {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(resolvePath('src/definition.php'));
		$builder->useAutowiring(false);
		$container = $builder->build();
	}
	return $container;
}

/**
 * Resolves relative path into absolute one relative to root directory.
 * @param string $path Path to resolve.
 * @return string Resolved path.
 */
function resolvePath(string $path): string {
	return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.preg_replace('/[\\\\\/]+/', DIRECTORY_SEPARATOR, $path);
}

function handleRoute(string $requestMethod, string $requestUri, array $routeInfo): ?array {
	switch ($routeInfo[0]) {
		case Dispatcher::NOT_FOUND:
		case Dispatcher::METHOD_NOT_ALLOWED:
			throw new Exception('Dispatcher error: NOT_FOUND or METHOD_NOT_ALLOWED');
		case Dispatcher::FOUND:
			$handler = $routeInfo[1];
			if (is_callable($handler)) {
				return $handler($requestMethod, $requestUri, $routeInfo[2]);
			} elseif (is_string($handler)) {
				if (class_exists($handler))
					return (new $handler($requestMethod, $requestUri, $routeInfo[2]))->{strtolower($requestMethod)}();
				else
					throw new Exception("Class \"{$handler}\" not found", 500);
			} else {
				throw new Exception("Unknown handler {$handler}");
			}
	}
}
