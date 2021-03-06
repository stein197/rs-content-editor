<?php

namespace App\Middleware\Verification;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

class Installation extends Controller {

	public function handle(Request $request, Response $response): Response {
		if ($this->app->config()->installed)
			return $response;
		if ($request->path() === '/')
			return $response->view('form.database')->terminate();
		else
			return $response->redirect('/');
	}
}
