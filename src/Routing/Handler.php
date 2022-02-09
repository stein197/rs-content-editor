<?php

namespace App\Routing;

use Exception;
use FastRoute\Dispatcher;
use App\Controller\HtmlStatic;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Http\TerminateException;
use function App\container;

class Handler {

	public function __construct(private $routeInfo) {}

	public function handle(Request $request): Response {
		/** @var Response */
		$response = container()->make(Response::class);
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				return self::getNotFoundResponse($request, $response);
			case Dispatcher::METHOD_NOT_ALLOWED:
				return $response->status(Status::METHOD_NOT_ALLOWED);
			case Dispatcher::FOUND:
				try {
					foreach ($this->routeInfo[1] as $handler) {
						$response = $this->getResult($handler, $request, $response);
						switch ($response->getType()) {
							case Response::TYPE_REDIRECT:
								break 2;
							case Response::TYPE_NOT_FOUND:
								return self::getNotFoundResponse($request, $response);
						}
					}
				} catch (TerminateException $ex) {
					$response = $ex->getResponse();
				}
				return $response;
		}
	}

	private static function getNotFoundResponse(Request $request, Response $response): Response {
		return (new HtmlStatic())->handle($request, $response->status(Status::NOT_FOUND));
	}

	private function getResult(mixed $handler, Request $request, Response $response): Response {
		if (is_callable($handler)) {
			return $handler($request, $response);
		} elseif (is_string($handler)) {
			if (class_exists($handler))
				return (new $handler())->handle($request, $response);
			else
				throw new Exception("Class \"{$handler}\" not found", 500);
		} else {
			throw new Exception("Unknown handler {$handler}");
		}
	}
}
