<?php

namespace App\Controller\Api;

use App\Controller;
use App\Editor\Entity;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Http\Status;

final class TypeEntities extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = Type::get($typeID);
		return $type ? $response->json(array_map(fn (Entity $entity): array => $entity->getProperties(), $type->getEntities())) : $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => "Тип с id {$typeID} не существует"
			]
		]);
	}
}
