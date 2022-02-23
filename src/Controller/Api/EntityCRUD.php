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

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$entityID = +$request->param('entityID');
		$type = Type::get($typeID);
		if (!$type)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Типа с ID {$typeID} не существует"
				]
			]);
		$entity = Entity::get($type, $entityID);
		if ($entity) {
			return $response->json(array_merge([
				'id' => $entity->getID()
			], $entity->getProperties()));
		} else {
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => "Сущности с ID {$entityID} не существует"
				]
			]);
		}
		return $response->json('OK');
	}

	public function post(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = Type::get($typeID);
		if (!$type)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Типа с ID {$typeID} не существует"
				]
			]);
		try {
			$entity = new Entity();
			$entity->setType($type);
			$entity->setProperties(json_decode($request->psr()->getBody()->getContents(), true));
			$entity->save();
			return $response->json([
				'success' => [
					'message' => "Сущность была успешно добавлена"
				]
			]);
		} catch (\Exception $ex) {
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => $ex->getMessage()
				]
			]);
		}
	}

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
