<?php
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Klein\Klein;
use function App\container;

error_reporting(E_ALL ^ E_DEPRECATED);

(function (): void {
	/** @var Klein */
	$klein = container()->get(Klein::class);
	$routeFunction = require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'route.php';
	$routeFunction($klein);
	$klein->dispatch();
})();
