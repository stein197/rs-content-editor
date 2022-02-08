<?php
namespace App\Http;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use App\HttpException;
use App\View;

class Response {

	public function __construct(public readonly ResponseInterface $response) {}

	public function json(array $data): self {
		return $this->body(json_encode($data));
	}

	public function view(string $name, ?array $data = null): self {
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
		throw new HttpException($this);
	}

	// TODO
	public function cookie(string $key, ?string $value): self {}

	public function header(string $key, null | string | array $value): self {
		return new self($this->response->withHeader($key, $value));
	}

	public function body($resource): self {
		return new self($this->response->withBody(Utils::streamFor($resource)));
	}
}
