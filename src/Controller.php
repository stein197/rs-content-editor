<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Route controller base class.
 * @method ResponseInterface get(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface head(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface post(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface put(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface delete(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface connect(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface options(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface trace(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface patch(RequestInterface $request, ResponseInterface $response, array $requestVars)
 */
abstract class Controller {

	public function __construct() {}

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		return $this->{strtolower($request->getMethod())}($request, $response, $requestVars);
	}
}
