<?php

use App\Http\Request;
use App\Http\Response;
use App\Middleware\Verification\Installation;
use App\Middleware\Verification\Connection;
use App\Middleware\Verification\AdminUser;
use App\Middleware\Verification\Auth;
use App\Middleware\Prettifier;
use App\Middleware\Minifier;
use App\Controller\Api\Users;
use App\Controller\Api\Types;
use App\Controller\Api\TypeCRUD;
use App\Controller\Api\TypeEntities;
use App\Controller\Api\EntityCRUD;
use App\Controller\Api\TypeProps;
use App\Controller\Api\TypePropertiesCRUD;
use App\Controller\Import;
use App\Controller\Export;
use App\Controller\Install;
use App\Controller\Index;
use App\Controller\Login;
use App\Controller\Logout;
use App\Middleware\Verification\CanEditUsers;
use App\Routing\Builder;
use function App\app;

return function (Builder $b) {
	$b->group('/', function (Builder $b): void {
		$b->before(Installation::class, Connection::class, AdminUser::class, Auth::class)->group('/', function (Builder $b): void {
			$b->group('/api/', function (Builder $b): void {
				$b->before(CanEditUsers::class)->group('/users/', function (Builder $b): void {
					$b->get('/current/', fn (Request $request, Response $response): Response => $response->json(app()->session()->user))->without(CanEditUsers::class);
					$b->match(['GET', 'POST'], '/', Users::class);
					$b->group('/{id:\d+}/', function (Builder $b): void {
						$b->match(['GET', 'PUT', 'DELETE'], '/', Users::class);
					});
				});
				$b->get('/types/[{id:\d+}/]', Types::class);
				$b->group('/type/', function (Builder $b): void {
					$b->post('/', TypeCRUD::class);
					$b->group('/{id:\d+}/', function (Builder $b): void {
						$b->match(['GET', 'PUT', 'DELETE'], '/', TypeCRUD::class);
						$b->post('/', EntityCRUD::class);
						$b->match(['GET', 'PUT', 'DELETE'], '/{entityID:\d+}/', EntityCRUD::class);
						$b->get('/entities/', TypeEntities::class);
						$b->group('/props/', function (Builder $b): void {
							$b->match(['GET', 'POST'], '/', TypeProps::class);
							$b->match(['PUT', 'DELETE'], '/{propID:\d+}/', TypeProps::class);
						});
						$b->group('/properties/', function (Builder $b): void {
							$b->post('/', TypePropertiesCRUD::class);
							$b->match(['GET', 'PUT', 'DELETE'], '/{name}/', TypePropertiesCRUD::class);
						});
					});
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
