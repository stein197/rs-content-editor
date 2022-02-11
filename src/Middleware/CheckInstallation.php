<?php

namespace App\Middleware;

use mysqli;
use mysqli_sql_exception;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use App\Database;
use function App\route;

class CheckInstallation extends Controller {

	public function handle(Request $request, Response $response): Response {
		foreach (Database::CREDENTIALS_CONFIG as $prop)
			if ($this->app->config()->db?->{$prop['name']} === null)
				if ($request->path() === '/')
					return $response->view('form', self::createViewVars())->terminate();
				else
					return $response->view('form', self::createViewVars())->redirect('/');
		try {
			$this->app->container()->get(mysqli::class);
		} catch (mysqli_sql_exception $ex) {
			if ($request->path() === '/')
				return $response->view('form', self::createViewVars("Не удалось подключиться к базе данных: {$ex->getMessage()}"))->terminate();
			else
				return $response->view('form', self::createViewVars("Не удалось подключиться к базе данных: {$ex->getMessage()}"))->redirect('/');
		}
		return $response;
	}

	private static function createViewVars(?string $errorMessage = null): array {
		$result = [
			'fields' => Database::CREDENTIALS_CONFIG,
			'button' => 'Подключиться и установить',
			'action' => route('install')
		];
		if ($errorMessage)
			$result['error'] = [
				'message' => $errorMessage
			];
		return $result;
	}
}
