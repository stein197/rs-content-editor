<?php

namespace App;

use Exception;
use FastRoute\Dispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\HtmlStatic;

class RouteHandler {

	public function __construct(private string $requestMethod, private string $requestUri, private $routeInfo) {}

	public function handle(RequestInterface $request): ResponseInterface {
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				return (new HtmlStatic())->handle($request, container()->make(ResponseInterface::class)->withStatus(404), $this->routeInfo[2] ?? []);
			case Dispatcher::METHOD_NOT_ALLOWED:
				return container()->make(ResponseInterface::class)->withStatus(404);
			case Dispatcher::FOUND:
				/** @var ResponseInterface */
				$response = container()->make(ResponseInterface::class);
				try {
					foreach ($this->routeInfo[1] as $handler) {
						if (is_callable($handler)) {
							$result = $handler($request, $response, $this->routeInfo[2]);
						} elseif (is_string($handler)) {
							if (class_exists($handler))
								$result = (new $handler())->handle($request, $response, $this->routeInfo[2] ?? []);
							else
								throw new Exception("Class \"{$handler}\" not found", 500);
						} else {
							throw new Exception("Unknown handler {$handler}");
						}
						if ($result instanceof ResponseInterface)
							$response = $result;
						else
							$response->getBody()->write(is_array($result) ? json_encode($result) : $result);
					}
				} catch (HttpException $ex) {
					$response = $ex->getResponse();
				}
				return $response;
		}
	}
}
