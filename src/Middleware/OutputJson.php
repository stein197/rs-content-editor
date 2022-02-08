<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Controller;

class OutputJson extends Controller {

	public function handle(Request $request, Response $response): Response {
		return $response->header('Content-Type', 'application/json');
	}
}
