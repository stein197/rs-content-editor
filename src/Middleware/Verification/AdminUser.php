<?php

namespace App\Middleware\Verification;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\route;

class AdminUser extends Controller {

	public function handle(Request $request, Response $response): Response {
		if ($this->app->db()?->hasAdminUser())
			return $response;
		return $response->view('form.auth', [
			'button' => 'Создать',
			'title' => 'Создание администратора',
			'action' => route('install')
		])->terminate();
	}
}
