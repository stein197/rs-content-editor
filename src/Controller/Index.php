<?php
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Controller;

class Index extends Controller {

	public function handle(Request $request, Response $response): Response {
		return $response->view('index', [
			'index' => true
		]);
	}
}
