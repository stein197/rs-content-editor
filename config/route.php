<?php

use App\Controller\Index;
use App\Controller\HtmlStatic;
use App\Middleware\LoadDatabase;
use App\Middleware\LoadDotEnv;
use App\Middleware\OutputJson;
use App\RouteBuilder;

return function (RouteBuilder $r) {
	$r->before(OutputJson::class, LoadDotEnv::class, LoadDatabase::class)->group('/api', function (RouteBuilder $r): void {
		$r->get('/', Index::class);
	});
	$r->get('/', HtmlStatic::class);
};
