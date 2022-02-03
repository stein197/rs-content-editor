<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use FastRoute\Dispatcher;
use function App\container;
use function App\handleRoute;

error_reporting(E_ALL ^ E_DEPRECATED);

(function (): void {
	ob_start();
	$container = container();
	/** @var Dispatcher */
	$dispatcher = $container->get(Dispatcher::class);
	$requestMethod = $container->get('request.method');
	$requestUri = explode('?', $container->get('request.uri'))[0];
	$routeInfo = $dispatcher->dispatch($requestMethod, $requestUri);
	$ob = ob_get_clean();
	$result = handleRoute($requestMethod, $requestUri, $routeInfo);
	// TODO: Move headers to middleware
	if ($result) {
		header('Content-Type: application/json');
		file_put_contents('php://output', json_encode($result));
	}
	if ($ob) {
		header('Content-Type: text/html');
		file_put_contents('php//output', $ob);
	}
})();
