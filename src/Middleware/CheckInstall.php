<?php

namespace App\Middleware;

use mysqli;
use mysqli_sql_exception;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\app;
use function App\route;

class CheckInstall extends Controller {

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
			if (app()->config()->db?->{$prop} === null)
				return $response->view('form', [
					'fields' => self::FORM_FIELDS,
					'button' => 'Подключиться и установить',
					'action' => route('install')
				])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		try {
			app()->container()->get(mysqli::class);
		} catch (mysqli_sql_exception $ex) {
			return $response->view('form', [
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				],
				'fields' => self::FORM_FIELDS,
				'button' => 'Подключиться и установить',
				'action' => route('install')
			])->{$request->path() === '/' ? 'terminate' : 'redirect'}('/');
		}
		return $response;
	}
}
