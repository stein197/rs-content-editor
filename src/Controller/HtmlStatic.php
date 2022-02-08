<?php
namespace App\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller;
use App\View;
use function App\view;

class HtmlStatic extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): View | ResponseInterface | string | array {
		return view('index', [
			'spa' => true
		]);
	}
}
