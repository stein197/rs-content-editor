<?php

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\Index;
use App\Middleware\LoadDatabase;
use App\Middleware\LoadDotEnv;
use App\Middleware\OutputHtml;
use App\Middleware\OutputJson;
use App\RouteBuilder;
use function App\resolvePath;

return function (RouteBuilder $r) {
	$r->before(OutputJson::class, LoadDotEnv::class, LoadDatabase::class)->group('/api', function (RouteBuilder $r): void {
		$r->get('/', Index::class);
	});
	$r->before(OutputHtml::class)->get('{path:.+}', function (RequestInterface $request, ResponseInterface $response): ResponseInterface {
		ob_start();
		require resolvePath('public/index.html');
		$response->getBody()->write(ob_get_clean());
		return $response;
	});
};
