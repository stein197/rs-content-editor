<?php

use App\Middleware\CheckInstallation;
use App\Middleware\CheckConnection;
use App\Middleware\CheckAdminUser;
use App\Middleware\CheckAuth;
use App\Middleware\Prettifier;
use App\Middleware\Minifier;
use App\Controller\Install;
use App\Controller\Index;
use App\Controller\Login;
use App\Routing\Builder;
use function App\app;

return function (Builder $b) {
	$b->group('/', function (Builder $b): void {
		$b->before(CheckInstallation::class, CheckConnection::class, CheckAdminUser::class, CheckAuth::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->get('/', fn ($a, $b) => $b->json(['msg' => 'ok']));
			});
			$b->get('/', Index::class);
		});
		$b->post('/install/', Install::class)->name('install');
		$b->post('/login/', Login::class)->name('login');
	})->finally(str_starts_with(app()->config()->mode ?? "production", "d") ? Prettifier::class : Minifier::class);
};
