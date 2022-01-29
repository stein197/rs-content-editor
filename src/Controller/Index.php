<?php
namespace App\Controller;

use App\Controller;

class Index extends Controller {

	public function get(): void {
		echo 'Hello from Index::get, $vars: '.var_export($this->requestVars, true);
	}
}
