<?php

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

return [
	Dispatcher::class => simpleDispatcher(require __DIR__.DIRECTORY_SEPARATOR.'route.php'),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
];
