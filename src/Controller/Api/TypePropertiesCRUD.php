<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Http\Status;
use function App\string2bool;
use function json_decode;

final class TypePropertiesCRUD extends Controller {

	public function get(Request $request, Response $response): Response {}

	public function post(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		$data = $this->retrieveData($request, $response);
		$type->setProperty($data['name'], $data['type'] === 'number' ? (
			+$data['value']
		) : ($data['type'] === 'boolean' ? (
			string2bool($data['value'])
		) : (
			$data['value']
		)));
		$type->save();
		return $response->json([
			'success' => [
				'message' => 'Свойство было успешно создано'
			]
		]);
	}

	public function delete(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		$type->unsetProperty($request->param('name'));
		$type->save();
		return $response->json([
			'success' => [
				'message' => "Свойство {$request->param('name')} было успешно удалено"
			]
		]);
	}

	public function put(Request $request, Response $response): Response {
		return $this->post($request, $response);
	}

	private function retrieveType(Request $request, Response $response): Type {
		$type = Type::get(+$request->param('id'));
		return $type ?? $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => "Типа с ID {$type} не существует"
			]
		])->terminate();
	}

	private function retrieveData(Request $request, Response $response): array {
		$data = json_decode($request->psr()->getBody()->getContents(), true);
		return !$data['name'] || !$data['type'] ? $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => 'Неверный формат данных'
			]
		])->terminate() : $data;
	}
}
