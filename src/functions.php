<?php

namespace App;

use stdClass;
use DI\Container;
use DI\ContainerBuilder;

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

function array2object(array $data): object {
	$result = new stdClass;
	foreach ($data as $key => $value)
		$result->{$key} = is_array($value) && !array_is_list($value) ? array2object($value) : $value;
	return $result;
}

function object2array(stdClass $data): array {
	$result = (array) $data;
	foreach ($result as $key => &$value)
		$value = $value instanceof stdClass ? object2array($value) : $value;
	return $result;
}
