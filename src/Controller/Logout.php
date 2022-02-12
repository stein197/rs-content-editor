<?php

namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

class Logout extends Controller {

	public function handle(Request $request, Response $response): Response {
		if ($this->app->session()->user)
			$this->logout();
		return $response->redirect('/');
	}

	private function logout(): void {
		unset($this->app->session()->user);
		$this->app->session()->save();
	}
}
