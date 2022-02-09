<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Controller;
use function App\config;

class LoadConfig extends Controller {

	private const REQUIRED_PROPERTIES = [
		'db.host', 'db.user', 'db.password', 'db.name'
	];

	public function handle(Request $request, Response $response): Response {
		config()->load();
		foreach (self::REQUIRED_PROPERTIES as $prop)
			if (config()->get($prop) === null)
				if ($request->path() === '/')
					$response->view('config')->terminate();
				else
					return $response->view('config')->redirect('/');
		return $response;
	}
}
