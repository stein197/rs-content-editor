<?php

namespace App;

use stdClass;
use FastRoute\RouteParser\Std as RouteParser;
use DI\Container;
use DI\ContainerBuilder;
use App\Routing\Router;
use InvalidArgumentException;

const HTTP_METHODS = [
	'GET',
	'HEAD',
	'POST',
	'PUT',
	'DELETE',
	'CONNECT',
	'OPTIONS',
	'TRACE',
	'PATCH'
];

function init(): void {
	config()->load();
}

function container(): Container {
	static $container;
	if (!$container) {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(resolvePath('config/definition.php'));
		$builder->useAutowiring(true);
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
	return DIRECTORY_SEPARATOR === '/' && $path[0] === '/' || DIRECTORY_SEPARATOR === '\\' && preg_match('/^[a-z]:[\\\\\/]/i', $path) ? $path : normalizePath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.preg_replace('/[\\\\\/]+/', DIRECTORY_SEPARATOR, $path));
}

function normalizePath(string $path): string {
	$result = [];
	foreach (preg_split('/[\\\\\/]+/', $path) as $part) {
		if ($part === '.') {
			continue;
		} elseif ($part === '..') {
			array_pop($result);
		} elseif ($part) {
			$result[] = $part;
		}
	}
	return join(DIRECTORY_SEPARATOR, $result);
}

function config(): Config {
	return container()->get(Config::class);
}

function route(string $name, ?array $params = null): ?string {
	/** @var Router */
	$router = container()->get(Router::class);
	$routeInfo = $router->getRouteByName($name);
	if (!$routeInfo)
		return null;
	/** @var RouteParser */
	$routeParser = container()->get(RouteParser::class);
	$parseInfo = $routeParser->parse($routeInfo->getRoute());
	$result = '';
	foreach ($parseInfo as $segment) {
		foreach ($segment as $part) {
			if (is_string($part)) {
				$result .= $part;
			} elseif (is_array($part)) {
				[$partName, $partRegex] = $part;
				if (!$params || !$params[$partName])
					throw new InvalidArgumentException("Unable to resolve route with name \"{$name}\": Passed array does not contain placeholder with name \"{$partName}\"");
				if (!preg_match("/{$partRegex}/", $params[$partName]))
					throw new InvalidArgumentException("Unable to resolve route with name \"{$name}\": Placeholder with name \"{$partName}\" does not match /{$partRegex}/ pattern");
				$result .= $params[$partName];
			}
		}
	}
	return $result;
}

function array2object(array $data): object {
	$result = new stdClass;
	foreach ($data as $key => $value)
		$result->{$key} = is_array($value) ? array2object($value) : $value;
	return $result;
}

function object2array(stdClass $data): array {
	$result = (array) $data;
	foreach ($result as $key => &$value)
		$value = $value instanceof stdClass ? object2array($value) : $value;
	return $result;
}
