<?php
namespace App\Controller;

use stdClass;
use mysqli;
use mysqli_sql_exception;
use App\Controller;
use App\Http\Request;
use App\Http\Response;

use function App\resolvePath;

class Install extends Controller {

	private const CREDENTIALS = [
		'db' => [
			'host' => [
				'required' => true
			],
			'user' => [
				'required' => true
			],
			'password' => [],
			'name' => [
				'required' => true
			]
		],
		'user' => [
			'name' => [
				'required' => true
			],
			'password' => [
				'required' => true
			]
		]
	];

	public function post(Request $request, Response $response): Response {
		if ($this->app->config()->installed && $this->app->db()?->hasAdminUser())
			return $response->notFound();
		$this->validate($request, $response);
		if ($request->post()->db)
			$this->install($request, $response);
		elseif ($request->post()->user)
			$this->createAdminUser($request, $response);
		return $response;
	}

	private function validate(Request $request, Response $response): void {
		foreach (self::CREDENTIALS as $name => $config)
			if ($request->post()->{$name})
				$crName = $name;
		if ($crName) {
			foreach (self::CREDENTIALS[$crName] as $name => $config)
				if ($request->post()->{$crName}->{$name} === null || $config['required'] && !$request->post()->{$crName}->{$name})
					$response->with([
						'error' => [
							'message' => "Не заполнено поле: {$name}"
						]
					])->redirect('/');
		} else {
			$response->with([
				'error' => [
					'message' => 'Отсутствуют данные'
				]
			])->redirect('/');
		}
	}

	private function install(Request $request, Response $response): void {
		$credentials = $request->post()->db;
		try {
			$mysqli = new mysqli($credentials->host, $credentials->user, $credentials->password, $credentials->name);
		} catch (mysqli_sql_exception $ex) {
			$response->with([
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				]
			])->redirect('/');
		}
		if ($mysqli->multi_query(file_get_contents(resolvePath('install.sql')))) {
			$config = $this->app->config();
			$config->db = new stdClass;
			foreach (self::CREDENTIALS['db'] as $name => $opts)
				$config->db->{$name} = $credentials->{$name};
			$config->installed = true;
			$config->save();
			$response->with([
				'success' => [
					'message' => 'База данных была успешно проинициализирована'
				]
			])->redirect('/');
		} else {
			$response->with([
				'error' => [
					'message' => "Не удалось проинициализировать базу данных: {$mysqli->error}"
				]
			])->redirect('/');
		}
	}

	private function createAdminUser(Request $request, Response $response): void {
		$credentials = $request->post()->user;
		$this->app->db()->createAdminUser($credentials->name, $credentials->password);
		$response->with([
			'success' => [
				'message' => 'Администратор был успешно добавлен'
			]
		])->redirect('/');
	}
}
