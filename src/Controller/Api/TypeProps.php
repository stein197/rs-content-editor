<?php

namespace App\Controller\Api;

use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Editor\Type;
use App\Editor\Prop;
use App\Http\Status;
use function json_decode;

final class TypeProps extends Controller {

	public function get(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		return $response->json(
			array_map(
				fn (Prop $prop): array => [
					'id' => $prop->getID(),
					'name' => $prop->getName(),
					'type' => $prop->getTypeAsString(),
					'required' => $prop->getRequired(),
					'format' => $prop->getFormat()
				],
				$type->getProps()
			)
		);
	}

	public function post(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		$data = $this->retrieveData($request, $response);
		$type->addProp(new Prop($data['name'], Prop::stringType2Const($data['type']), $data['required'], $data['format']));
		$type->save();
		return $response->json([
			'success' => [
				'message' => 'Свойство типа было успешно создано'
			]
		]);
	}

	public function put(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		$data = $this->retrieveData($request, $response);
		$prop = $type->getPropByID(+$request->param('propID'));
		if (!$prop)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Свойства с ID {$request->param('propID')} не существует"
				]
			]);
		if ($prop->getName() !== $data['name']) {
			$newNameEscaped = $this->app->db()->escape($data['name']);
			$this->app->db()->mysqli()->query("ALTER TABLE `e_{$type->getID()}` RENAME COLUMN `{$prop->getNameEscaped()}` TO `{$newNameEscaped}`");
		}
		$prop->setName($data['name']);
		$prop->setType(Prop::stringType2Const($data['type']));
		$prop->setFormat($data['format']);
		$prop->setRequired($data['required']);
		$prop->save();
		return $response->json([
			'error' => [
				'message' => "Свойство {$prop->getID()} было успешно изменено"
			]
		]);
	}

	public function delete(Request $request, Response $response): Response {
		$type = $this->retrieveType($request, $response);
		$prop = $type->getPropByID(+$request->param('propID'));
		if (!$prop)
			return $response->status(Status::NOT_FOUND)->json([
				'error' => [
					'message' => "Свойства с ID {$request->param('propID')} не существует"
				]
			]);
		$type->deleteProp($prop);
		return $response->json([
			'success' => [
				'message' => "Свойство было успешно удалено"
			]
		]);
	}

	private function retrieveType(Request $request, Response $response): Type {
		$type = Type::get(+$request->param('id'));
		return $type ?: $response->status(Status::BAD_REQUEST)->json([
			'error' => [
				'message' => "Тип с id {$request->param('id')} не существует"
			]
		])->terminate();
	}

	private function retrieveData(Request $request, Response $response): array {
		$data = json_decode($request->psr()->getBody()->getContents(), true);
		if (!$data['name'] || !$data['type'])
			return $response->status(Status::BAD_REQUEST)->json([
				'error' => [
					'message' => 'Неверный формат данных'
				]
			])->terminate();
		return $data;
	}
}
