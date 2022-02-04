<?php
namespace App\Controller;

use App\Controller;

class Index extends Controller {

	public function get(string $requestUri, array $requestVars): void {
		echo 'Hello from Index::get, $vars: '.var_export($requestVars, true);
	}
}
