<?php

use App\Middleware\CheckSetup;
use App\Middleware\Prettifier;
use App\Controller\Setup;
use App\Controller\Index;
use App\Controller\HtmlStatic;
use App\Routing\Builder;

return function (Builder $b) {
	$b->group('/', function (Builder $b): void {
		$b->before(CheckSetup::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->get('/', Index::class);
			});
			$b->get('/', HtmlStatic::class);
		});
		$b->post('/setup/', Setup::class)->name('setup');
	})->finally(Prettifier::class);
};
