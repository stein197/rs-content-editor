<?php
namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use function App\app;

class Install extends Controller {

	private const DB_CREDENTIALS_NAMES = [
		'host', 'user', 'password', 'name'
	];

	public function post(Request $request, Response $response): Response {
		if (app()->installed())
			return $response->notFound();
		self::verifyRequestData($request, $response);
		foreach (self::DB_CREDENTIALS_NAMES as $name)
			app()->config()->db->{$name} = $request->post()->db->{$name};
		app()->config()->save();
		app()->install();
		return $response->redirect('/');
	}

	private static function verifyRequestData(Request $request, Response $response): void {
		if (!$request->post()->db)
			$response->terminate();
		foreach (self::DB_CREDENTIALS_NAMES as $name)
			if ($request->post()->db->{$name} === null)
				$response->terminate();
	}
}
