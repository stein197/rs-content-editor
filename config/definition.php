<?php

use App\RouteBuilder;
use function App\resolvePath;

return [
	RouteBuilder::class => new RouteBuilder(require resolvePath('config/route.php')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
];
