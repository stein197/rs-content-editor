<?php

namespace App;

use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;

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
	return realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.preg_replace('/[\\\\\/]+/', DIRECTORY_SEPARATOR, $path));
}

function sendResponse(ResponseInterface $request): void {
	foreach ($request->getHeaders() as $name => $values)
		foreach ($values as $value)
			header(sprintf('%s: %s', $name, $value), false);
	file_put_contents('php://output', $request->getBody());
}
