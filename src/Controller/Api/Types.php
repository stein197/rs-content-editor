<?php

namespace App\Controller\Api;

use App\Controller;
use App\Editor\Type;
use App\Http\Request;
use App\Http\Response;

final class Types extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = $request->param('id') ? +$request->param('id') : 0;
		$types = Type::getByParentID($typeID);
		return $response->json(array_map(fn (Type $type): array => [
			'id' => $type->getID(),
			'name' => $type->getName(),
			'properties' => $type->getProperties(),
		], $types));
	}
}
