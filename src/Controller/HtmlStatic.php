<?php
namespace App\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;
use function App\resolvePath;

class HtmlStatic extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		ob_start();
		require resolvePath('public/index.html');
		$response->getBody()->write(ob_get_clean());
		return $response->withHeader('Content-Type', 'text/html');
	}
}
