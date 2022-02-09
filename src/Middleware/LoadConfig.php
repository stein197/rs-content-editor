<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\config;

class LoadConfig extends Controller {

	private const REQUIRED_PROPERTIES = [
		'host', 'user', 'password', 'name'
	];

	public function handle(Request $request, Response $response): Response {
		foreach (self::REQUIRED_PROPERTIES as $prop)
			if (config()->db?->{$prop} === null)
				return $response->view('config', [
					'fields' => [
						[
							'label' => 'Хост',
							'name' => 'db[host]'
						],
						[
							'label' => 'Пользователь',
							'name' => 'db[user]'
						],
						[
							'label' => 'Пароль',
							'name' => 'db[password]',
							'type' => 'password'
						],
						[
							'label' => 'Имя базы данных',
							'name' => 'db[name]'
						]
					]
				])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		return $response;
	}
}
