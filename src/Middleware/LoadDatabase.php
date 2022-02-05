<?php

namespace App\Middleware;

use mysqli;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;
use function App\container;
use function App\terminate;

class LoadDatabase extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		try {
			container()->get(mysqli::class);
			return $response;
		} catch (Exception $ex) {
			$response->getBody()->write(json_encode([
				'error' => [
					'msg' => 'Неверно настроены данные для подключения к базе данных'
				]
			]));
			terminate($response->withStatus(500));
		}
	}
}
