<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Editor\Prop;
use App\Http\Status;

final class TypeProps extends Controller {

	public function get(Request $request, Response $response): Response {
		$typeID = +$request->param('id');
		$type = Type::get($typeID);
		return $type ? $response->json(
			array_map(
				fn (Prop $prop): array => [
					'name' => $prop->getName(),
					'type' => $prop->getTypeAsString(),
					'required' => $prop->getRequired(),
				],
				$type->getProps()
			)
		) : $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => "Тип с id {$typeID} не существует"
			]
		]);
	}
}
