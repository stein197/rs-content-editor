<?php

namespace App;

use App\Http\Request;
use App\Http\Response;
use App\Http\TerminateException;

/**
 * Route controller base class.
 * @method Response get(Request $request, Response $response)
 * @method Response head(Request $request, Response $response)
 * @method Response post(Request $request, Response $response)
 * @method Response put(Request $request, Response $response)
 * @method Response delete(Request $request, Response $response)
 * @method Response connect(Request $request, Response $response)
 * @method Response options(Request $request, Response $response)
 * @method Response trace(Request $request, Response $response)
 * @method Response patch(Request $request, Response $response)
 */
abstract class Controller {

	public function __construct() {}

	/**
	 * Обрабатывает запрос и возвращает ответ.
	 * @param Request $request
	 * @param Response $response
	 * @return Response
	 * @throws TerminateException Если был вызов метода `terminate()`
	 */
	public function handle(Request $request, Response $response): Response {
		return $this->{strtolower($request->psr()->getMethod())}($request, $response);
	}
}
