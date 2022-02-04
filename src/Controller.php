<?php

namespace App;

/**
 * Route controller base class.
 */
abstract class Controller {

	public function __construct() {}

	public function handle(string $requestMethod, string $requestUri, array $requestVars): void {
		$this->{strtolower($requestMethod)}($requestUri, $requestVars);
	}

	public function get(string $requestUri, array $requestVars): void {}

	public function head(string $requestUri, array $requestVars): void {}

	public function post(string $requestUri, array $requestVars): void {}

	public function put(string $requestUri, array $requestVars): void {}

	public function delete(string $requestUri, array $requestVars): void {}

	public function connect(string $requestUri, array $requestVars): void {}

	public function options(string $requestUri, array $requestVars): void {}

	public function trace(string $requestUri, array $requestVars): void {}

	public function patch(string $requestUri, array $requestVars): void {}
}
