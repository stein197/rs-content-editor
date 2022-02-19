<?php

namespace App\Controller;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Editor\Entity;
use const JSON_PRETTY_PRINT;

// TODO
class Export extends Controller {

	public function get(Request $request, Response $response): Response {
		$data = self::collectData(0);
		$json = json_encode($data, JSON_PRETTY_PRINT);
		return $response->body($json)->download('content.json');
		// return $response->json($data);
	}

	private static function collectData(int $typeID): array {
		$result = [];
		foreach (Type::getByParentID($typeID) as $type) {
			if ($type->getProperties()) {
				$result[$type->getName()] = (array) $type->getProperties();
				$result[$type->getName()] = array_merge($result[$type->getName()], self::collectData($type->getID()));
			} else {
				$result[$type->getName()] = array_map(fn (Entity $entity): array => array_filter($entity->getProperties(), fn ($val) => $val !== null), $type->getEntities());
			}
		}
		return $result;
	}
}
