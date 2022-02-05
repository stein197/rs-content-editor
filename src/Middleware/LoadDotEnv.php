<?php

namespace App\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use App\Controller;
use function App\terminate;
use function App\resolvePath;

class LoadDotEnv extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		$envFilePath = resolvePath('.env');
		if (!file_exists($envFilePath))
			touch($envFilePath);
		$dotEnv = Dotenv::createImmutable(resolvePath('.'));
		$dotEnv->load();
		try {
			$dotEnv->required(['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME']);
			return $response;
		} catch (ValidationException $ex) {
			$response->getBody()->write(json_encode([
				'error' => [
					'msg' => $ex->getMessage()
				]
			]));
			terminate($response->withStatus(500));
		}
	}
}
