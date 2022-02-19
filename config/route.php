<?php

use App\Middleware\Verification\Installation;
use App\Middleware\Verification\Connection;
use App\Middleware\Verification\AdminUser;
use App\Middleware\Verification\Auth;
use App\Middleware\Prettifier;
use App\Middleware\Minifier;
use App\Controller\Api\User;
use App\Controller\Api\Users;
use App\Controller\Api\Types;
use App\Controller\Api\Type;
use App\Controller\Api\TypeEntities;
use App\Controller\Api\TypeProps;
use App\Controller\Import;
use App\Controller\Export;
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
				$b->get('/users/', Users::class);
				$b->get('/types/[{id:\d+}/]', Types::class);
				$b->group('/type/{id:\d+}/', function (Builder $b): void {
					$b->get('/', Type::class);
					$b->get('/entities/', TypeEntities::class);
					$b->get('/props/', TypeProps::class);
				});
			});
			$b->get('/', Index::class);
		});
		$b->post('/install/', Install::class)->name('install');
		$b->post('/login/', Login::class)->name('login');
		$b->post('/logout/', Logout::class);
		$b->post('/import/', Import::class);
		$b->get('/export/', Export::class);
	})->finally(str_starts_with(app()->config()->mode ?? "production", "d") ? Prettifier::class : Minifier::class);
};
