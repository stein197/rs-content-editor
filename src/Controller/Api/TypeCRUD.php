<?php

namespace App\Controller\Api;

use App\Editor\Entity;
use App\Controller;
use App\Editor\Type;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use function json_decode;

final class TypeCRUD extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = \App\Editor\Type::get($typeID);
		if ($type) {
			$parent = $type->getParent();
			return $response->json([
				'hasID' => true,
				'incrementFrom' => $type->getIncrementFrom(),
				'parent' => $parent ? $parent->getID() : 0,
				'storeInParent' => !!$type->getStoreInParent(),
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

	public function post(Request $request, Response $response): Response {
		$data = json_decode($request->psr()->getBody()->getContents(), true);
		if (!$data['name'])
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => 'Неверный формат данных'
				]
			]);
		$data['increment_from'] = isset($data['increment_from']) ? +$data['increment_from'] : 1;
		$data['parent'] = isset($data['parent']) ? +$data['parent'] : 0;
		$data['name'] = $this->app->db()->escape($data['name']);
		$data['store_in_parent'] = isset($data['store_in_parent']) ? +$data['store_in_parent'] : 0;
		(new Type($data['name'], $data['parent'], $data['store_in_parent'], $data['increment_from']))->save();
		return $response->json([
			'success' => [
				'message' => 'Тип был успешно создан'
			]
		]);
	}

	public function delete(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = \App\Editor\Type::get($typeID);
		if (!$type)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Тип с id {$typeID} не существует"
				]
			]);
		$type->delete();
		return $response->json([
			'succes' => [
				'message' => "Тип {$typeID} был успешно удалён"
			]
		]);
	}
}
