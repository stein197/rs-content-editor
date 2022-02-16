<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;

final class Type extends Controller {

	public function get(Request $request, Response $response): Response {
		$type = \App\Editor\Type::get(+$request->param('id'));
		return $type ? $response->json([
			'id' => $type->getID(),
			'name' => $type->getName(),
			'properties' => $type->getProperties()
		]) : $response->json(null);
	}
}
