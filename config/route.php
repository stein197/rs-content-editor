<?php

use FastRoute\RouteCollector;
use App\Controller\Index;
use function App\resolvePath;

return function (RouteCollector $r) {
	$r->addGroup('/api', function (RouteCollector $r): void {
		$r->get('/', Index::class);
	});

	$r->get('{path:.+}', function (string $requestMethod, string $requestUri, array $requestVars): void {
		require resolvePath('public/index.html');
	});
};
