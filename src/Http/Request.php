<?php
namespace App\Http;

use stdClass;
use Psr\Http\Message\RequestInterface;
use function App\container;
use function App\array2object;

class Request {

	private ?array $params = null;
	private stdClass $get;
	private stdClass $post;
	private string $path;
	private ?string $query;

	public function __construct(private RequestInterface $request, array $get, array $post, private array $cookie, private array $files) {
		$this->get = array2object($get);
		$this->post = array2object($post);
		[$this->path, $this->query] = explode('?', $request->getUri());
	}

	public function get(): stdClass {
		return $this->get;
	}

	public function post(): stdClass {
		return $this->post;
	}

	public function path(): string {
		return $this->path;
	}

	public function query(): ?string {
		return $this->query;
	}

	public function cookie(string $key): ?string {
		return @$this->cookie[$key];
	}
	
	public function param(string $key): ?string {
		return @$this->params[$key];
	}

	// TODO
	public function file(string $path) {}

	public function psr(): RequestInterface {
		return $this->request;
	}

	public function setParams(?array $params): void {
		if ($this->params === null)
			$this->params = $params;
	}

	public static function current(): self {
		static $request;
		if (!$request)
			$request = new self(container()->get(RequestInterface::class), $_GET, $_POST, $_COOKIE, $_FILES);
		return $request;
	}
}
