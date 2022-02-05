<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Route controller base class.
 * @method ResponseInterface|string|array get(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array head(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array post(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array put(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array delete(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array connect(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array options(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array trace(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|string|array patch(RequestInterface $request, ResponseInterface $response, array $requestVars)
 */
abstract class Controller {

	public function __construct() {}

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): Template | ResponseInterface | string | array {
		return $this->{strtolower($request->getMethod())}($request, $response, $requestVars);
	}
}
