<?php
namespace App\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;
use App\Template;
use function App\template;

class HtmlStatic extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): Template | ResponseInterface | string | array {
		return template('index', [
			'spa' => true
		]);
	}
}
