<?php

use App\Config;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Routing\Builder;
use function DI\create;
use function DI\get;
use function App\resolvePath;
use function App\config;

return [
	FastRoute\RouteParser\Std::class => create(FastRoute\RouteParser\Std::class),
	RequestInterface::class => create(Request::class)->constructor(get('request.method'), get('request.uri'), get('request.headers'), file_get_contents('php://input'), get('request.protocol')),
	ResponseInterface::class => create(Response::class)->constructor(200, ['Content-Type' => 'text/html']),
	App\Http\Response::class => create(App\Http\Response::class)->constructor(get(ResponseInterface::class)),
	Builder::class => create()->constructor(get('config.route')),
	Config::class => create()->constructor(resolvePath('config.json')),
	mysqli::class => create()->constructor(get('db.host'), get('db.user'), get('db.password'), get('db.name')),
	'request.method' => $_SERVER['REQUEST_METHOD'],
	'request.uri' => $_SERVER['REQUEST_URI'],
	'request.headers' => getallheaders(),
	'request.protocol' => $_SERVER['SERVER_PROTOCOL'],
	'db.host' => fn () => config()->db?->host,
	'db.user' => fn () => config()->db?->user,
	'db.password' => fn () => config()->db?->password,
	'db.name' => fn () => config()->db?->name,
	'config.route' => fn () => require resolvePath('config/route.php'),
	'response.404' => fn () => require resolvePath('config/404.php')
];
