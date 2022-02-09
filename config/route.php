<?php

use App\Controller\Index;
use App\Controller\HtmlStatic;
use App\Middleware\LoadConfig;
use App\Middleware\LoadDatabase;
use App\Middleware\OutputJson;
use App\Middleware\HtmlPrettifier;
use App\Routing\Builder;

return function (Builder $b) {
	$b->before(LoadConfig::class, LoadDatabase::class)->group('/', function (Builder $b): void {
		$b->before(OutputJson::class)->group('/api', function (Builder $b): void {
			$b->get('/', Index::class);
		});
		$b->get('/', HtmlStatic::class)->after(HtmlPrettifier::class);
	});
};
