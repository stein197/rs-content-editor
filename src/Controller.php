<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Route controller base class.
 * @method ResponseInterface|Template|string|array get(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array head(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array post(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array put(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array delete(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array connect(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array options(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array trace(RequestInterface $request, ResponseInterface $response, array $requestVars)
 * @method ResponseInterface|Template|string|array patch(RequestInterface $request, ResponseInterface $response, array $requestVars)
 */
abstract class Controller {

	public function __construct() {}

	/**
	 * 
	 * @param RequestInterface $request 
	 * @param ResponseInterface $response 
	 * @param array $requestVars 
	 * @return Template|ResponseInterface|string|array 
	 * @throws HttpException В случае вызова функции `terminate()`.
	 */
	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): Template | ResponseInterface | string | array {
		return $this->{strtolower($request->getMethod())}($request, $response, $requestVars);
	}
}
