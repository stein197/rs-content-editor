<?php

use App\Middleware\CheckInstall;
use App\Middleware\Prettifier;
use App\Controller\Install;
use App\Controller\Index;
use App\Controller\HtmlStatic;
use App\Routing\Builder;

return function (Builder $b) {
	$b->group('/', function (Builder $b): void {
		$b->before(CheckInstall::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->get('/', Index::class);
			});
			$b->get('/', HtmlStatic::class);
		});
		$b->post('/install/', Install::class)->name('setup');
	})->finally(Prettifier::class);
};
