<?php

namespace App\Routing;

use Exception;
use FastRoute\Dispatcher;
use App\Controller\HtmlStatic;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Http\TerminateException;
use App\Http\TerminateType;

use function App\container;

class Handler {

	public function __construct(private $routeInfo) {}

	public function handle(Request $request): Response {
		/** @var Response */
		$response = container()->make(Response::class);
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				return container()->get('response.404')($request, $response);
			case Dispatcher::METHOD_NOT_ALLOWED:
				return $response->status(Status::METHOD_NOT_ALLOWED);
			case Dispatcher::FOUND:
				try {
					foreach ($this->routeInfo[1]['main'] as $handler) {
						$response = $this->getResult($handler, $request, $response);
					}
				} catch (TerminateException $ex) {
					$response = $ex->getResponse();
					switch ($ex->getCode()) {
						case TerminateType::REDIRECT:
							break;
						case TerminateType::NOT_FOUND:
							return container()->get('response.404')($request, $response);
					}
				}
				if ($this->routeInfo[1]['finally'])
					foreach ($this->routeInfo[1]['finally'] as $handler)
						$response = $this->getResult($handler, $request, $response);
				return $response;
		}
	}

	private function getResult(mixed $handler, Request $request, Response $response): Response {
		if (is_callable($handler)) {
			return $handler($request, $response);
		} elseif (is_string($handler)) {
			if (class_exists($handler))
				return container()->make($handler)->handle($request, $response);
			else
				throw new Exception("Class \"{$handler}\" not found", 500);
		} else {
			throw new Exception("Unknown handler {$handler}");
		}
	}
}
