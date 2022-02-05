<?php

namespace App;

use Exception;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RouteHandler {

	public function __construct(private string $requestMethod, private string $requestUri, private $routeInfo) {}

	public function handle(RequestInterface $request): ResponseInterface {
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				return new Response(404);
			case Dispatcher::METHOD_NOT_ALLOWED:
				return new Response(405);
			case Dispatcher::FOUND:
				$response = new Response();
				try {
					foreach ($this->routeInfo[1] as $handler) {
						if (is_callable($handler)) {
							$response = $handler($request, $response, $this->routeInfo[2]);
						} elseif (is_string($handler)) {
							if (class_exists($handler))
								$response = (new $handler())->handle($request, $response, $this->routeInfo[2]);
							else
								throw new Exception("Class \"{$handler}\" not found", 500);
						} else {
							throw new Exception("Unknown handler {$handler}");
						}
						if (!$response)
							return container()->get(ResponseInterface::class);
					}
				} catch (HttpException $ex) {
					$response = $ex->getResponse();
				}
				return $response;
		}
	}
}
