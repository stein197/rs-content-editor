<?php

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use App\RouteBuilder;
use function DI\create;
use function DI\get;
use function App\resolvePath;

return [
	RequestInterface::class => create(Request::class)->constructor(get('request.method'), get('request.uri'), get('request.headers'), file_get_contents('php://input'), get('request.protocol')),
	RouteBuilder::class => create()->constructor(get('config.route')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
	'request.headers' => getallheaders(),
	'request.protocol' => $_SERVER['SERVER_PROTOCOL'],
	'config.route' => fn () => require resolvePath('config/route.php')
];
