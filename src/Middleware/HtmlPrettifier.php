<?php

namespace App\Middleware;

use Tidy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Utils;
use App\Controller;

class HtmlPrettifier extends Controller {

	public function handle(RequestInterface $request, ResponseInterface $response, array $requestVars): ResponseInterface {
		$tidy = new Tidy;
		$tidy->parseString($response->getBody(), [
			'indent' => true,
			'indent-spaces' => 4,
			'indent-with-tabs' => true,
			'tab-size' => 4,
			'drop-empty-elements' => false,
			'drop-empty-paras' => false,
			'wrap' => 0
		], 'utf8');
		$tidy->cleanRepair();
		return $response->withBody(Utils::streamFor((string) $tidy));
	}
}
