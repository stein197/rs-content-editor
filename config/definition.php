<?php

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Routing\Builder;
use function DI\create;
use function DI\get;
use function App\resolvePath;

return [
	RequestInterface::class => create(Request::class)->constructor(get('request.method'), get('request.uri'), get('request.headers'), file_get_contents('php://input'), get('request.protocol')),
	ResponseInterface::class => create(Response::class)->constructor(200, ['Content-Type' => 'text/html']),
	Builder::class => create()->constructor(get('config.route')),
	mysqli::class => create()->constructor(get('db.host'), get('db.user'), get('db.password'), get('db.name')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
	'request.headers' => getallheaders(),
	'request.protocol' => $_SERVER['SERVER_PROTOCOL'],
	'db.host' => fn () => $_ENV['DB_HOST'],
	'db.user' => fn () => $_ENV['DB_USER'],
	'db.password' => fn () => $_ENV['DB_PASSWORD'],
	'db.name' => fn () => $_ENV['DB_NAME'],
	'config.route' => fn () => require resolvePath('config/route.php')
];
