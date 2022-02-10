<?php
namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\app;

class Install extends Controller {

	public function post(Request $request, Response $response): Response {
		app()->config()->db->host = $request->post()->db->host;
		app()->config()->db->user = $request->post()->db->user;
		app()->config()->db->password = $request->post()->db->password;
		app()->config()->db->name = $request->post()->db->name;
		app()->config()->save();
		return $response->redirect('/');
	}
}
