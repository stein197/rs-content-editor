<?php

use App\Controller\Index;
use App\Http\Middleware\OutputHtml;
use App\Http\Middleware\OutputJson;
use App\RouteBuilder;
use function App\resolvePath;

return function (RouteBuilder $r) {
	$r->before(OutputJson::class)->group('/api', function (RouteBuilder $r): void {
		$r->get('/', Index::class);
	});

	$r->before(OutputHtml::class)->get('{path:.+}', function (string $requestMethod, string $requestUri, array $requestVars): void {
		require resolvePath('public/index.html');
	});
};
