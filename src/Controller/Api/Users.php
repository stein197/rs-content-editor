<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

class Users extends Controller {

	public function get(Request $request, Response $response): Response {
		$user = $this->app->session()->user;
		if ($user->admin == 0)
			return $response->json([]);
		return $response->json($this->app->db()->getUsers());
	}
}
