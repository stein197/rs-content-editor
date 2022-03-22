<?php
namespace App\Http;

final class Status {

	public const MOVED_PERMANENTLY = 301;
	public const BAD_REQUEST = 400;
	public const FORBIDDEN = 403;
	public const NOT_FOUND = 404;
	public const METHOD_NOT_ALLOWED = 405;

	private function __construct() {}
}
