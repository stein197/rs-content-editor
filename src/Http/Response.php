<?php
namespace App\Http;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use App\Http\TerminateException;
use App\View;
use function basename;
use function mime_content_type;
use function App\resolvePath;
use function App\app;
use function App\array2object;

class Response {

	private array $cookie = [];
	
	public function __construct(private ResponseInterface $response) {}

	public function json($data): self {
		return $this->header('Content-Type', 'application/json')->body(json_encode($data, \JSON_UNESCAPED_UNICODE));
	}

	public function view(string $name, array $data = []): self {
		return $this->header('Content-Type', 'text/html')->body((new View(app(), $name, $data))->render());
	}

	public function file(string $path): self {
		$path = resolvePath($path);
		return $this->header('Content-Type', mime_content_type($path))->body(file_get_contents($path));
	}

	public function downloadFile(string $path, ?string $name = null): self {
		$name = $name ?? basename($path);
		return $this->file(resolvePath($path))->headers([
			'Content-Type' => 'application/octet-stream',
			'Content-Transfer-Encoding' => 'Binary',
			'Content-Disposition' => "attachment; filename=\"{$name}\"",
		]);
	}

	public function download(string $name): self {
		return $this->headers([
			'Content-Type' => 'application/octet-stream',
			'Content-Transfer-Encoding' => 'Binary',
			'Content-Disposition' => "attachment; filename=\"{$name}\"",
		]);
	}

	public function status(int $status): self {
		return $this->response->getStatusCode() === $status ? $this : $this->withResponse($this->response->withStatus($status));
	}

	public function with(array $vars): self {
		app()->session()->vars = array2object($vars);
		app()->session()->save();
		return $this;
	}

	// TODO
	public function back() {}

	public function redirect(string $path, int $status = Status::MOVED_PERMANENTLY) {
		$this->header('Location', $path)->status($status)->terminate(TerminateType::REDIRECT);
	}

	public function notFound() {
		$this->status(Status::NOT_FOUND)->terminate(TerminateType::NOT_FOUND);
	}

	public function terminate(int $code = -1) {
		throw new TerminateException($this, "", $code);
	}

	public function cookie(string $key, ?string $value = null, int $expires = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false): self {
		$result = clone $this;
		$result->cookie[] = [
			'key' => $key,
			'value' => $value,
			'expires' => $expires,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httpOnly' => $httpOnly
		];
		return $result;
	}

	public function header(string $key, null | string | array $value): self {
		return $this->withResponse($value ? $this->response->withHeader($key, $value) : $this->response->withoutHeader($key));
	}

	public function headers(array $values): self {
		$response = $this;
		foreach ($values as $key => $value)
			$response = $response->header($key, $value);
		return $response;
	}

	public function body($resource): self {
		return $this->withResponse($this->response->withBody(Utils::streamFor($resource)));
	}

	public function psr(): ResponseInterface {
		return $this->response;
	}

	private function withResponse(ResponseInterface $response): self {
		$result = clone $this;
		$result->response = $response;
		return $result;
	}

	public static function send(self $response): void {
		foreach ($response->response->getHeaders() as $name => $values)
			foreach ($values as $value)
				header(sprintf('%s: %s', $name, $value), false);
		foreach ($response->cookie as $cookie)
			setcookie($cookie['key'], $cookie['value'], $cookie['value'] === null ? -1 : $cookie['expires'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
		http_response_code($response->response->getStatusCode());
		file_put_contents('php://output', $response->response->getBody());
	}
}
