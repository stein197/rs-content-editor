<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Route controller base class.
 * @method ResponseInterface|View|string|array get(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array head(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array post(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array put(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array delete(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array connect(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array options(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array trace(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|View|string|array patch(RequestInterface $request, ResponseInterface $response, array $requestVars)
 */
abstract class Controller {

	public function __construct() {}

	/**
	 * 
	 * @param RequestInterface $request 
	 * @param ResponseInterface $response 
	 * @param array $requestVars 
	 * @return View|ResponseInterface|string|array 
	 * @throws HttpException В случае вызова функции `terminate()`.
	 */
	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): View | ResponseInterface | string | array {
		return $this->{strtolower($request->getMethod())}($request, $response, $requestVars);
	}
}
