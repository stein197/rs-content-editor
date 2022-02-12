<?php

namespace App\Middleware\Verification;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\route;

class Auth extends Controller {

	public function handle(Request $request, Response $response): Response {
		if ($this->app->session()->user)
			return $response;
		if ($request->path() === '/')
			return $response->view('form.auth', [
				'button' => 'Войти',
				'title' => 'Авторизация',
				'action' => route('login')
			])->terminate();
		return $response->redirect('/');
	}
}
