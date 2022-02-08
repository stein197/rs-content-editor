<?php

namespace App\Middleware;

use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\resolvePath;

class LoadDotEnv extends Controller {

	private const REQUIRED_VARS = [
		'DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME'
	];

	public function handle(Request $request, Response $response): Response {
		$envFilePath = resolvePath('.env');
		if (!file_exists($envFilePath))
			touch($envFilePath);
		$dotEnv = Dotenv::createImmutable(resolvePath('.'));
		$dotEnv->load();
		try {
			$dotEnv->required(self::REQUIRED_VARS);
			return $response;
		} catch (ValidationException $ex) {
			$response->json([
				'error' => [
					'msg' => 'Не установлена одна из переменных в .env-файле: '.join(', ', self::REQUIRED_VARS)
				]
			])->status(500)->terminate();
		}
	}
}
