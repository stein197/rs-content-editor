<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Controller;

class Minifier extends Controller {

	public function handle(Request $request, Response $response): Response {
		foreach ($response->psr()->getHeader('Content-Type') as $contentType) {
			switch ($contentType) {
				case 'application/json':
					return $this->minifyJson($request, $response);
				case 'text/html':
					return $this->minifyHtml($request, $response);
				default:
					return $response;
			}
		}
	}

	private function minifyHtml(Request $request, Response $response): Response {
		$html = $response->psr()->getBody()->getContents();
		$search = [
			'/\>[^\S ]+/s',
			'/[^\S ]+\</s',
			'/(\s)+/s',
			'/<!--(.|\s)*?-->/'
		];
		$replace = array('>', '<', '\\1');
		return $response->body(preg_replace($search, $replace, $html));
	}

	private function minifyJson(Request $request, Response $response): Response {
		return $response->body(json_encode(json_decode($response->psr()->getBody()->getContents())));
	}
}
