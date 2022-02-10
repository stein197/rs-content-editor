<?php
namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\config;

class Install extends Controller {

	public function post(Request $request, Response $response): Response {
		config()->db->host = $request->post()->db->host;
		config()->db->user = $request->post()->db->user;
		config()->db->password = $request->post()->db->password;
		config()->db->name = $request->post()->db->name;
		config()->save();
		return $response->redirect('/');
	}
}
