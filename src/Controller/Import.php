<?php

namespace App\Controller;

use stdClass;
use JsonException;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Editor\Type;
use function json_decode;
use const JSON_THROW_ON_ERROR;

// TODO
class Import extends Controller {

	public function post(Request $request, Response $response): Response {
		$this->app->db()->truncateData();
		try {
			$data = json_decode($request->psr()->getBody()->getContents(), false, JSON_THROW_ON_ERROR);
		} catch (JsonException $ex) {
			return $response->json([
				'error' => [
					'message' => $ex->getMessage()
				]
			])->status(Status::BAD_REQUEST);
		}
		unset($data->newGifts); // TODO: Временно исключаем
		foreach ($data as $typeName => $typeDescriptor)
			if ($typeDescriptor instanceof stdClass) // TODO: Добавить обработку сущностей
				$this->createType($typeName, $typeDescriptor);
		return $response->json([
			'success' => [
				'message' => 'Данные импортированы успешно'
			]
		]);
	}

	private function createType(string $name, stdClass $descriptor, ?Type $parent = null): void {
		$properties = new stdClass;
		$childTypes = [];
		foreach ($descriptor as $key => $value) {
			if ($value instanceof stdClass) {
				$childTypes[] = [$key, $value];
			} elseif (is_array($value)) {
				// TODO: Items
			} else {
				$properties->{$key} = $value;
			}
		}
		$type = new Type($name);
		$type->setProperties($properties);
		if ($parent)
			$type->setParent($parent);
		$type->save();
		foreach ($childTypes as $child)
			$this->createType($child[0], $child[1], $type);
	}
}
