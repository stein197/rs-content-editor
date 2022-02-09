<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Psr\Container\ContainerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Routing\Router;
use function App\init;
use function App\container;

error_reporting(E_ALL ^ (E_DEPRECATED | E_WARNING));

(function (ContainerInterface $container): void {
	init();
	$request = Request::current();
	/** @var Router */
	$router = $container->get(Router::class);
	$handler = $router->dispatch($request->psr()->getMethod(), explode('?', $request->psr()->getUri())[0]);
	$response = $handler->handle($request);
	Response::send($response);
})(container());
