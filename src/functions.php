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

/**
 * Отправляет результат вместе в телом, заголовками и кодом ответа на клиент.
 * @param ResponseInterface $response Ответ, отправляемый на клиент.
 * @return void 
 */
function send(ResponseInterface $response): void {
	foreach ($response->getHeaders() as $name => $values)
		foreach ($values as $value)
			header(sprintf('%s: %s', $name, $value), false);
	http_response_code($response->getStatusCode());
	file_put_contents('php://output', $response->getBody());
}

/**
 * Прекращает цепочку работы контроллеров.
 * @param ResponseInterface $response Ответ, немедленно отправляемый на клиент.
 * @throws HttpException
 */
function terminate(ResponseInterface $response): never {
	throw new HttpException($response);
}

function template(string $name, ?array $vars = []): Template {
	return new Template($name, $vars);
}
