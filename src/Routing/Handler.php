<?php

namespace App\Routing;

use Exception;
use FastRoute\Dispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\HtmlStatic;
use App\HttpException;
use App\Template;
use RuntimeException;

use function App\container;

class Handler {

	public function __construct(private string $requestMethod, private string $requestUri, private $routeInfo) {}

	public function handle(RequestInterface $request): ResponseInterface {
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				$response = container()->make(ResponseInterface::class)->withStatus(404);
				$result = (new HtmlStatic())->handle($request, $response, $this->routeInfo[2] ?? []);
				return $this->createResponse($result, $response);
			case Dispatcher::METHOD_NOT_ALLOWED:
				return container()->make(ResponseInterface::class)->withStatus(405);
			case Dispatcher::FOUND:
				/** @var ResponseInterface */
				$response = container()->make(ResponseInterface::class);
				try {
					foreach ($this->routeInfo[1] as $handler) {
						$result = $this->getResult($handler, $request, $response);
						$response = $this->createResponse($result, $response);
					}
				} catch (HttpException $ex) {
					$response = $this->createResponse($ex->getResponse(), $response);
				}
				return $response;
		}
	}

	private function getResult(mixed $handler, RequestInterface $request, ResponseInterface $response): mixed {
		if (is_callable($handler)) {
			return $handler($request, $response, $this->routeInfo[2]);
		} elseif (is_string($handler)) {
			if (class_exists($handler))
				return (new $handler())->handle($request, $response, $this->routeInfo[2] ?? []);
			else
				throw new Exception("Class \"{$handler}\" not found", 500);
		} else {
			throw new Exception("Unknown handler {$handler}");
		}
	}

	private function createResponse(mixed $result, ResponseInterface $response): ResponseInterface {
		if ($result instanceof ResponseInterface) {
			return $result;
		} else {
			switch (true) {
				case $result instanceof Template:
					$result = $result->render();
					break;
				case is_array($result):
					$result = json_encode($result);
					break;
			}
			$response->getBody()->write($result);
			return $response;
		}
	}
}
