<?php

namespace App\Middleware;

use Tidy;
use GuzzleHttp\Psr7\Utils;
use App\Http\Request;
use App\Http\Response;
use App\Controller;

class HtmlPrettifier extends Controller {

	public function handle(Request $request, Response $response): Response {
		$tidy = new Tidy;
		$tidy->parseString($response->response()->getBody(), [
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
}
