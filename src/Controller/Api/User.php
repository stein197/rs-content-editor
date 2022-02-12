<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

class User extends Controller {

	public function get(Request $request, Response $response): Response {
		return $response->json($this->app->session()->user);
	}
}
