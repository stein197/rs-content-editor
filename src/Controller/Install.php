<?php
namespace App\Controller;

use stdClass;
use mysqli;
use mysqli_sql_exception;
use App\Controller;
use App\Http\Request;
use App\Http\Response;

class Install extends Controller {

	private const DB_CREDENTIALS_NAMES = [
		'host', 'user', 'password', 'name'
	];

	public function post(Request $request, Response $response): Response {
		if ($this->app->config()->installed)
			return $response->notFound();
		$this->validate($request, $response);
		try {
			$this->tryConnect($request->post()->db);
		} catch (mysqli_sql_exception $ex) {
			return $response->with([
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				]
			])->redirect('/');
		}
		$this->install();
		return $response->body('<b>it works!</b>');
	}

	private function validate(Request $request, Response $response): void {
		$db = $request->post()->db;
		if (!$db)
			$response->with([
				'error' => [
					'message' => 'Не заполнено ни одно поле'
				]
			])->redirect('/');
		foreach (self::DB_CREDENTIALS_NAMES as $name)
			if ($db->{$name} === null)
				$response->with([
					'error' => [
						'message' => "Не заполнено поле: {$name}"
					]
				])->redirect('/');
	}

	private function install(): void {
		// TODO: setup database
		$this->app->config()->installed = true;
		$this->app->config()->save();
	}

	/**
	 * @throws mysqli_sql_exception Если не удалось подключиться к БД с использованием переданных данных.
	 */
	private function tryConnect(stdClass $credentials): void {
		new mysqli($credentials->host, $credentials->user, $credentials->password, $credentials->name);
	}
}
