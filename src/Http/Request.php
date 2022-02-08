<?php
namespace App\Http;

use Psr\Http\Message\RequestInterface;

use function App\container;

class Request {

	public function __construct(public RequestInterface $request, public array $query, public array $vars) {}

	public function body(): string {
		return $this->request->getBody()->getContents();
	}

	// TODO
	public function cookie(string $key): string {}
	
	// TODO
	public function file(string $path) {}

	public function request(): RequestInterface {
		return $this->request;
	}

	public function query(): array {
		return $this->query;
	}

	public function vars(): array {
		return $this->vars;
	}
}
