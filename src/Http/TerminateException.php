<?php
namespace App\Http;

use Exception;
use App\Http\Response;

class TerminateException extends Exception {

	public function __construct(private Response $response, ?string $message = null, ?int $code = null) {
		parent::__construct($message, $code);
	}

	public function getResponse(): Response {
		return $this->response;
	}
}
