<?php
namespace App\Controller;

use stdClass;
use mysqli;
use mysqli_sql_exception;
use App\Controller;
use App\Database;
use App\Installer;
use App\Http\Request;
use App\Http\Response;
use function App\route;

class Install extends Controller {

	private const DB_CREDENTIALS_NAMES = [
		'host', 'user', 'password', 'name'
	];

	public function post(Request $request, Response $response): Response {
		/** @var Installer */
		$installer = $this->app->container()->get(Installer::class);
		if ($installer->installed())
			return $response->notFound();
		$this->validate($request, $response);
		try {
			$this->tryConnect($request->post()->db);
		} catch (mysqli_sql_exception $ex) {
			return $response->view('form', [
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				],
				'fields' => Database::CREDENTIALS_CONFIG,
				'button' => 'Подключиться и установить',
				'action' => route('install')
			]);
		}
		$installer->install();
		return $response->body('<b>it works!</b>');
	}

	private function validate(Request $request, Response $response): void {
		$db = $request->post()->db;
		if (!$db)
			$response->view('form', [
				'error' => [
					'message' => 'Не заполнены поля'
				],
				'fields' => Database::CREDENTIALS_CONFIG,
				'button' => 'Подключиться и установить',
				'action' => route('install')
			])->terminate();
		foreach (self::DB_CREDENTIALS_NAMES as $name)
			if ($request->post()->db->{$name} === null)
				$response->view('form', [
					'error' => [
						'message' => 'Не заполнены поля'
					],
					'fields' => Database::CREDENTIALS_CONFIG,
					'button' => 'Подключиться и установить',
					'action' => route('install')
				])->terminate();
	}

	private function tryConnect(stdClass $credentials): void {
		new mysqli($credentials->host, $credentials->user, $credentials->password, $credentials->name);
	}
}
