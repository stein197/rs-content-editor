<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\config;

class CheckSetup extends Controller {

	private const REQUIRED_PROPERTIES = [
		'host', 'user', 'password', 'name'
	];

	public function handle(Request $request, Response $response): Response {
		foreach (self::REQUIRED_PROPERTIES as $prop)
			if (config()->db?->{$prop} === null)
				return $response->view('form', [
					'fields' => [
						[
							'label' => 'Хост',
							'name' => 'db[host]',
							'default' => 'localhost',
							'required' => true
						],
						[
							'label' => 'Пользователь',
							'name' => 'db[user]',
							'default' => 'root',
							'required' => true
						],
						[
							'label' => 'Пароль',
							'name' => 'db[password]',
							'type' => 'password',
							'required' => true
						],
						[
							'label' => 'Имя базы данных',
							'name' => 'db[name]',
							'required' => true
						]
					]
				])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		return $response;
	}
}
