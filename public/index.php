<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use App\Router;
use App\RouteHandler;
use DI\Container;
use function App\container;

error_reporting(E_ALL ^ E_DEPRECATED);

(function (Container $container): void {
	ob_start();
	/** @var Router */
	$router = $container->get(Router::class);
	/** @var RouteHandler */
	$handler = $router->dispatch($container->get('request.method'), explode('?', $container->get('request.uri'))[0]);
	// /** @var Response */
	$result = $handler->handle();
	if ($result)
		file_put_contents('php://output', json_encode($result));
	file_put_contents('php://output', ob_get_clean());
})(container());
