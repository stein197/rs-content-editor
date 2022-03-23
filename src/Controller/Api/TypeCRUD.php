<?php

namespace App\Controller\Api;

use App\Editor\Entity;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;

// TODO
final class TypeCRUD extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = \App\Editor\Type::get($typeID);
		if ($type) {
			$incrementFrom = array_reduce($type->getEntities(), fn ($carry, Entity $item): int => $carry > $item->getID() ? $item->getID() : $carry, INF);
			$parent = $type->getParent();
			return $response->json([
				'hasID' => !!$type->getPropByName('id'),
				'incrementFrom' => $incrementFrom === INF ? 0 : $incrementFrom,
				'parent' => $parent ? $parent->getID() : 0,
				'storeInParent' => false,
				'id' => $type->getID(),
				'name' => $type->getName(),
				'properties' => $type->getProperties(),
			]);
		}
		return $response->status(Status::NOT_FOUND)->json([
			'error' => [
				'message' => "Тип с id {$typeID} не существует"
			]
		]);
	}
}
