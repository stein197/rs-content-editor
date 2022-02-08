<?php
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Controller;

class Index extends Controller {

	public function get(Request $request, Response $response): Response {
		return $response->json([
			'msg' => 'Hello from Index::get, $vars: '.var_export($request->vars, true)
		]);
	}
}
