<?php

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use function App\resolvePath;

return [
	Dispatcher::class => simpleDispatcher(require resolvePath('config/route.php')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
];
