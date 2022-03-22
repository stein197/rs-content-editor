<?php

namespace App\Middleware\Verification;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;

class CanEditUsers extends Controller {

	public function handle(Request $request, Response $response): Response {
		return $this->app->session()->user->admin == 0 ? $response->status(Status::FORBIDDEN)->json([
			'error' => [
				'message' => 'Нет доступа'
			]
		])->terminate() : $response;
	}
}
