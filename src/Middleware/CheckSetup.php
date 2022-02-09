<?php

namespace App\Middleware;

use mysqli;
use mysqli_sql_exception;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\config;
use function App\container;

class CheckSetup extends Controller {

	private const REQUIRED_PROPERTIES = [
		'host', 'user', 'password', 'name'
	];

	private const FORM_FIELDS = [
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
	];

	public function handle(Request $request, Response $response): Response {
		foreach (self::REQUIRED_PROPERTIES as $prop)
			if (config()->db?->{$prop} === null)
				return $response->view('form', [
					'fields' => self::FORM_FIELDS
				])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		try {
			container()->get(mysqli::class);
		} catch (mysqli_sql_exception $ex) {
			return $response->view('form', [
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				],
				'fields' => self::FORM_FIELDS
			])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		}
		return $response;
	}
}
