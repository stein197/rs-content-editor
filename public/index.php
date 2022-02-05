<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use DI\Container;
use Psr\Http\Message\RequestInterface;
use App\Routing\Router;
use function App\container;
use function App\sendResponse;

error_reporting(E_ALL ^ (E_DEPRECATED | E_WARNING));

(function (Container $container): void {
	/** @var RequestInterface */
	$request = $container->get(RequestInterface::class);
	/** @var Router */
	$router = $container->get(Router::class);
	$handler = $router->dispatch($request->getMethod(), explode('?', $request->getUri())[0]);
	$response = $handler->handle($request);
	sendResponse($response);
})(container());
