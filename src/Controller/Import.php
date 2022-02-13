<?php

namespace App\Controller;

use JsonException;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use function json_decode;
use const JSON_THROW_ON_ERROR;

// TODO
class Import extends Controller {

	public function post(Request $request, Response $response): Response {
		$this->app->db()->truncateData();
		try {
			$data = json_decode($request->psr()->getBody()->getContents(), false, JSON_THROW_ON_ERROR);
		} catch (JsonException $ex) {
			return $response->json([
				'error' => [
					'message' => $ex->getMessage()
				]
			])->status(Status::BAD_REQUEST);
		}
		return $response;
	}
}
