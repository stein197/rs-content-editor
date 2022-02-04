<?php

namespace App\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;

class OutputHtml extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		return $response->withHeader('Content-Type', 'text/html');
	}
}
