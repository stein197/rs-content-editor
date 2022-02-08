<?php

namespace App\Middleware;

use mysqli;
use Exception;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\container;

class LoadDatabase extends Controller {

	public function handle(Request $request, Response $response): Response {
		try {
			container()->get(mysqli::class);
			return $response;
		} catch (Exception $ex) {
			$response->json([
				'error' => [
					'msg' => 'Неверно настроены данные для подключения к базе данных'
				]
			])->status(500)->terminate();
		}
	}
}
