<?php

namespace App\Controller;

use stdClass;
use JsonException;
use App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Editor\Type;
use App\Editor\Prop;
use function json_decode;
use function is_array;
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
			$this->createType($typeName, $typeDescriptor);
		return $response->json([
			'success' => [
				'message' => 'Данные импортированы успешно'
			]
		]);
	}

	private function createType(string $name, stdClass | array $descriptor, ?Type $parent = null): void {
		$type = new Type($name);
		if ($parent)
			$type->setParent($parent);
		$type->save();
		if (is_array($descriptor) && array_is_list($descriptor)) {
			$this->addEntities($type, $descriptor);
		} else {
			foreach ($descriptor as $key => $value) {
				if ($value instanceof stdClass || is_array($value)) {
					$this->createType($key, $value, $type);
				} else {
					$type->setProperty($key, $value);
				}
			}
			$type->save();
		}
	}

	private function addEntities(Type $type, array $entityArray): void {
		foreach ($entityArray as $entity) {
			foreach ($entity as $propName => $propValue) {
				if (!$type->getPropByName($propName)) {
					$type->addProp(new Prop($propName, match (gettype($propValue)) {
						'boolean' => Prop::TYPE_BOOLEAN,
						'integer', 'double', 'float' => Prop::TYPE_NUMBER,
						'string' => Prop::TYPE_STRING,
						'array', 'object' => Prop::TYPE_JSON,
						default => Prop::TYPE_STRING
					}));
					$type->save();
				}
			}
		}
		$insertColumns = array_map(fn (Prop $prop): string => $prop->getNameEscaped(), $type->getProps());
		$insertValues = [];
		foreach ($entityArray as $entity) {
			$insertValue = [];
			foreach ($insertColumns as $colName) {
				$insertValue[] = match (gettype($entity->{$colName})) {
					'boolean' => +$entity->{$colName},
					'integer', 'double', 'float' => $entity->{$colName},
					'string' => '\''.$this->app->db()->escape($entity->{$colName}).'\'',
					'array', 'object' => '\''.json_encode($entity->{$colName}).'\'',
					'NULL' => 'NULL'
				};
			}
			$insertValues[] = $insertValue;
		}
		$insertColumns = join(
			',',
			array_map(
				fn (string $name): string => "`{$name}`",
				$insertColumns
			)
		);
		$insertValues = join(
			',',
			array_map(
				fn (array $entity): string => '('.join(',', $entity).')',
				$insertValues
			)
		);
		$query = "INSERT INTO `e_{$type->getID()}` ({$insertColumns}) VALUES {$insertValues}";
		$this->app->db()->mysqli()->query($query);
	}
}
