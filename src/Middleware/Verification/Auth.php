<?php

namespace App\Middleware\Verification;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\route;

class Auth extends Controller {

	// TODO
	public function handle(Request $request, Response $response): Response {
		return $response->view('form.auth', [
			'button' => 'Войти',
			'title' => 'Авторизация',
			'action' => route('login')
		])->terminate();
	}
}
