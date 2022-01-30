<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use FastRoute\Dispatcher;
use function App\init;
use function App\container;
use function App\handleRoute;

error_reporting(E_ALL ^ E_DEPRECATED);

(function (): void {
	init();
	$container = container();
	/** @var Dispatcher */
	$dispatcher = $container->get(Dispatcher::class);
	$requestMethod = $container->get('request.method');
	$requestUri = explode('?', $container->get('request.uri'))[0];
	$routeInfo = $dispatcher->dispatch($requestMethod, $requestUri);
	$result = handleRoute($requestMethod, $requestUri, $routeInfo);
	file_put_contents('php://output', json_encode($result));
})();
