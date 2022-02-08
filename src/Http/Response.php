<?php
namespace App\Http;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use App\Http\TerminateException;
use App\View;

class Response {

	public function __construct(private ResponseInterface $response) {}

	public function json(array $data): self {
		return $this->body(json_encode($data));
	}

	public function view(string $name, array $data = []): self {
		return $this->body((new View($name, $data))->render());
	}

	// TODO
	public function file(string $path): self {}

	public function status(int $status): self {
		return new self($this->response->withStatus($status));
	}

	public function redirect(string $path, ?int $status): never {
		$this->header('Location', $path)->status($status ?? $this->response->getStatusCode())->terminate();
	}

	public function terminate(): never {
		throw new TerminateException($this);
	}

	// TODO
	public function cookie(string $key, ?string $value): self {}

	public function header(string $key, null | string | array $value): self {
		return new self($value ? $this->response->withHeader($key, $value) : $this->response->withoutHeader($key));
	}

	public function body($resource): self {
		return new self($this->response->withBody(Utils::streamFor($resource)));
	}

	public function response(): ResponseInterface {
		return $this->response;
	}
}
