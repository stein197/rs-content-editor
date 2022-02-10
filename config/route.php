<?php

use App\Middleware\CheckInstall;
use App\Middleware\Prettifier;
use App\Middleware\Minifier;
use App\Controller\Install;
use App\Controller\Index;
use App\Routing\Builder;
use function App\app;

return function (Builder $b) {
	$mode = app()->config()->mode ?? "production";
	$b->group('/', function (Builder $b): void {
		$b->before(CheckInstall::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->get('/', fn ($a, $b) => $b->json(['msg' => 'ok']));
			});
			$b->get('/', Index::class);
		});
		$b->post('/install/', Install::class)->name('install');
	})->finally(str_starts_with($mode, "d") ? Prettifier::class : Minifier::class);
};
