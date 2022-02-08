<?php

namespace App\Routing;

use Exception;
use FastRoute\Dispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\HtmlStatic;
use App\Http\Request;
use App\Http\Response;
use App\HttpException;
use App\View;

use function App\container;

class Handler {

	public function __construct(private string $requestMethod, private string $requestUri, private $routeInfo) {}

	public function handle(RequestInterface $request, array $query): ResponseInterface {
		$req = new Request($request, $query ?? [], $this->routeInfo[2] ?? []);
		/** @var Response */
		$res = container()->make(Response::class);
		switch ($this->routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				return (new HtmlStatic())->handle($req, $res->status(404))->response();
			case Dispatcher::METHOD_NOT_ALLOWED:
				return $res->status(405)->response();
			case Dispatcher::FOUND:
				try {
					foreach ($this->routeInfo[1] as $handler)
						$res = $this->getResult($handler, $req, $res);
				} catch (HttpException $ex) {
					$res = $ex->getResponse();
				}
				return $res->response();
		}
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
