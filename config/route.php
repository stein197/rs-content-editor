<?php

use App\Middleware\Verification\Installation;
use App\Middleware\Verification\Connection;
use App\Middleware\Verification\AdminUser;
use App\Middleware\Verification\Auth;
use App\Middleware\Prettifier;
use App\Middleware\Minifier;
use App\Controller\Api\User;
use App\Controller\Install;
use App\Controller\Index;
use App\Controller\Login;
use App\Controller\Logout;
use App\Routing\Builder;
use function App\app;

return function (Builder $b) {
	$b->group('/', function (Builder $b): void {
		$b->before(Installation::class, Connection::class, AdminUser::class, Auth::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->get('/user/', User::class);
			});
			$b->get('/', Index::class);
		});
		$b->post('/install/', Install::class)->name('install');
		$b->post('/login/', Login::class)->name('login');
		$b->post('/logout/', Logout::class)->name('logout');
	})->finally(str_starts_with(app()->config()->mode ?? "production", "d") ? Prettifier::class : Minifier::class);
};
