<?php

namespace App;

/**
 * Route controller base class.
 */
abstract class Controller {

	public function __construct(protected readonly string $requestMethod, protected readonly string $requestUri, protected readonly array $requestVars) {}
}
