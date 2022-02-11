<?php

namespace App\Middleware;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

class CheckInstallation extends Controller {

	public function handle(Request $request, Response $response): Response {
		if (!$this->app->config()->installed)
			if ($request->path() === '/')
				return $response->view('form.database')->terminate();
			else
				return $response->redirect('/');
		return $response;
	}
}
