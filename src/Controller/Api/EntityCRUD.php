<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Editor\Entity;
use App\Http\Status;

// TODO
final class EntityCRUD extends Controller {

	public function get(Request $request, Response $response): Response {}
	public function post(Request $request, Response $response): Response {}
	public function put(Request $request, Response $response): Response {}

	public function delete(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$entityID = +$request->param('entityID');
		$type = Type::get($typeID);
		if (!$type)
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => "Типа с ID {$typeID} не существует"
				]
			]);
		$entity = Entity::get($type, $entityID);
		if ($entity) {
			$entity->delete();
			return $response->json([
				'success' => [
					'message' => "Сущность с ID {$entityID} была успешно удалена"
				]
			]);
		} else {
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => "Сущности с ID {$entityID} не существует"
				]
			]);
		}
	}
}
