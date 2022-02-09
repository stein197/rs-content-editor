<?php

use App\Middleware\CheckSetup;
use App\Middleware\OutputJson;
use App\Middleware\HtmlPrettifier;
use App\Controller\Setup;
use App\Controller\Index;
use App\Controller\HtmlStatic;
use App\Routing\Builder;

return function (Builder $b) {
	$b->before(CheckSetup::class)->group('/', function (Builder $b): void {
		$b->before(OutputJson::class)->group('/api', function (Builder $b): void {
			$b->get('/', Index::class);
		});
		$b->get('/', HtmlStatic::class)->after(HtmlPrettifier::class);
	});
	$b->post('/setup/', Setup::class)->name('setup');
};
