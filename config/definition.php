<?php

use App\RouteBuilder;
use function DI\create;
use function DI\get;
use function App\resolvePath;

return [
	RouteBuilder::class => create()->constructor(get('config.route')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
	'config.route' => fn () => require resolvePath('config/route.php')
];
