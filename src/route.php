<?php

use App\Controller\Index;
use FastRoute\RouteCollector;

return function(RouteCollector $r) {
	$r->addGroup('/api', function (RouteCollector $r): void {
		$r->get('/', Index::class);
	});

	$r->get('/', function (string $requestMethod, string $requestUri, array $requestVars): void {
		require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'index.html';
	});
};
