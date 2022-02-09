<?php
namespace App\Http;

use Psr\Http\Message\RequestInterface;
use function App\container;

class Request {

	private ?array $params;

	public function __construct(private RequestInterface $request, private array $get, private array $post, private array $cookie, private array $files) {}

	public function get(string $key): mixed {
		return @$this->get[$key];
	}

	public function post(string $key): mixed {
		return @$this->post[$key];
	}

	public function cookie(string $key): ?string {
		return @$this->cookie[$key];
	}
	
	// TODO
	public function file(string $path) {}

	public function psr(): RequestInterface {
		return $this->request;
	}

	public function param(string $key): ?string {
		return @$this->params[$key];
	}

	public function setParams(array $params): void {
		if (!$this->params)
			$this->params = $params;
	}

	public static function current(): self {
		static $request;
		if (!$request)
			$request = new self(container()->get(RequestInterface::class), $_GET, $_POST, $_COOKIE, $_FILES);
		return $request;
	}
}
