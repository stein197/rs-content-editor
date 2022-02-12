<?php

namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\route;
use function password_verify;

class Login extends Controller {

	public function handle(Request $request, Response $response): Response {
		if (!$request->post()->user)
			return $response->view('form.auth', [
				'error' => [
					'message' => 'Отсутствуют данные'
				],
				'button' => 'Войти',
				'title' => 'Авторизация',
				'action' => route('login')
			])->terminate();
		$this->authorize($request, $response);
		return $response->redirect('/');
		return $response;
	}

	private function authorize(Request $request, Response $response): void {
		$credentials = $request->post()->user;
		$user = $this->app->db()->getUserByName($credentials->name);
		if (!$user || !password_verify($credentials->password, $user->password))
			$response->with([
				'error' => [
					'message' => 'Неверный пользователь или пароль'
				]
			])->redirect('/');
		$this->app->session()->user = $user;
		$this->app->session()->save();
	}
}
