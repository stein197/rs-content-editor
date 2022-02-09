<?php

namespace App\Middleware;

use mysqli;
use mysqli_sql_exception;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\container;

class LoadDatabase extends Controller {

	public function handle(Request $request, Response $response): Response {
		try {
			container()->get(mysqli::class);
		} catch (mysqli_sql_exception $ex) {
			$response->view('config', [
				'error' => [
					'message' => 'Не удалось подключиться к БД: '.$ex->getMessage()
				]
			])->terminate();
		}
		return $response;
	}
}
