<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;

// TODO
final class TypeCRUD extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = \App\Editor\Type::get($typeID);
		return $type ? $response->json([
			'id' => $type->getID(),
			'name' => $type->getName(),
			'properties' => $type->getProperties(),
		]) : $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => "Тип с id {$typeID} не существует"
			]
		]);
	}
}
