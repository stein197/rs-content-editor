<?php
namespace App\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;

class Index extends Controller {

	public function get(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		$response->getBody()->write('Hello from Index::get, $vars: '.var_export($requestVars, true));
		return $response;
	}
}
