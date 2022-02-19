<?php

namespace App\Middleware;

use Tidy;
use App\Http\Request;
use App\Http\Response;
use App\Controller;
use const JSON_PRETTY_PRINT;

class Prettifier extends Controller {

	public function handle(Request $request, Response $response): Response {
		foreach ($response->psr()->getHeader('Content-Type') as $contentType) {
			switch ($contentType) {
				case 'application/json':
					return $this->prettifyJson($request, $response);
				case 'text/html':
					return $this->prettifyHtml($request, $response);
				default:
					return $response;
			}
		}
	}

	private function prettifyHtml(Request $request, Response $response): Response {
		$tidy = new Tidy();
		$tidy->parseString($response->psr()->getBody(), [
			'indent' => true,
			'indent-spaces' => 4,
			'indent-with-tabs' => true,
			'tab-size' => 4,
			'drop-empty-elements' => false,
			'drop-empty-paras' => false,
			'wrap' => 0
		], 'utf8');
		$tidy->cleanRepair();
		return $response->body((string) $tidy);
	}

	private function prettifyJson(Request $request, Response $response): Response {
		return $response->body(json_encode(json_decode($response->psr()->getBody()->getContents(), false, 512, \JSON_UNESCAPED_UNICODE), JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE));
	}
}
