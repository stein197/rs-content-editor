<?php
namespace App\Http;

use Psr\Http\Message\RequestInterface;

class Request {

	public function __construct(public readonly RequestInterface $request, public readonly array $vars, public readonly array $query) {}

	public function body(): string {
		return $this->request->getBody()->getContents();
	}

	// TODO
	public function cookie(string $key): string {}
	
	// TODO
	public function file(string $path) {}
}
