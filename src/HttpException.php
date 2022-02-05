<?php
namespace App;

use Exception;
use Psr\Http\Message\ResponseInterface;

class HttpException extends Exception {

	public function __construct(private ResponseInterface $response, ?string $message = null, ?int $code = null) {
		parent::__construct($message, $code);
	}

	public function getResponse(): ResponseInterface {
		return $this->response;
	}
}
