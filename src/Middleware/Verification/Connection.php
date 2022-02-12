<?php

namespace App\Middleware\Verification;

use mysqli;
use mysqli_sql_exception;
use App\Controller;
use App\Http\Request;
use App\Http\Response;

final class Connection extends Controller {

	public function handle(Request $request, Response $response): Response {
		try {
			$this->app->container()->get(mysqli::class);
		} catch (mysqli_sql_exception $ex) {
			return $response->view('error', [
				'error' => [
					'message' => "Не удалось подключиться к базе данных: {$ex->getMessage()}"
				]
			])->terminate();
		}
		return $response;
	}
}
