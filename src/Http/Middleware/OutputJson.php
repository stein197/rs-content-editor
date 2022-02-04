<?php

namespace App\Http\Middleware;

use App\Controller;

class OutputJson extends Controller {

	public function handle(string $requestMethod, string $requestUri, array $requestVars): void {
		header('Content-Type: application/json');
	}
}
