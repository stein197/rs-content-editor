<?php

namespace App\Editor;

use LogicException;
use InvalidArgumentException;
use Exception;
use DI\Definition\Exception\InvalidDefinition;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use function App\app;
use function is_numeric;
use function date_create_from_format;
use function date;
use const JSON_UNESCAPED_UNICODE;

// TODO
final class Entity {

	private int | string | null $id = null;
	private array $properties = [];
	private ?Type $type = null;

	public function __construct() {}

	public function getID(): int | string | null {
		return $this->id;
	}

	public function setType(Type $type): void {
		$this->type = $type;
	}

	public function setProperties(array $properties): void {
		$this->properties = $properties;
		unset($this->properties['id']);
	}

	public function getProperties(bool $format = false): array {
		if ($format) {
			$result = [];
			foreach ($this->properties as $k => $v) {
				$prop = $this->type->getPropByName($k);
				$result[$k] = $v && $prop->getType() === Prop::TYPE_DATE && $prop->getFormat() ? date($prop->getFormat(), date_create_from_format('Y-m-d', $v)->getTimestamp()) : $v;
			}
			return $result;
		}
		return $this->properties;
	}

	public function delete(): void {
		app()->db()->mysqli()->query("DELETE FROM `e_{$this->type->getID()}` WHERE `id` = '{$this->id}'");
	}

	public function save(): void {
		$mysqli = app()->db()->mysqli();
		if ($this->id === null) {
			$insertClause = [];
			$valueClause = [];
			foreach ($this->properties as $propName => $propValue) {
				$insertClause[] = "`{$propName}`";
				$valueClause[] = match (gettype($propValue)) {
					'boolean', 'integer', 'double' => +$propValue,
					default => $propValue ? '\''.app()->db()->escape($propValue).'\'' : 'NULL'
				};
			}
			$query = "INSERT INTO `e_{$this->type->getID()}` (".join(',', $insertClause).") VALUE (".join(',', $valueClause).")";
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
		} else {
			$setClause = [];
			foreach ($this->properties as $propName => $propValue) {
				$setClause[] = "`{$propName}` = ".match (gettype($propValue)) {
					'boolean', 'integer', 'double' => +$propValue,
					default => $propValue ? '\''.app()->db()->escape($propValue).'\'' : 'NULL'
				};
			}
			$query = "UPDATE `e_{$this->type->getID()}` SET ".join(', ', $setClause)." WHERE `id` = '{$this->id}'";
			$mysqli->query($query);
		}
	}

	/**
	 * @param Type $type 
	 * @return self[]
	 * @throws LogicException 
	 * @throws InvalidArgumentException 
	 * @throws Exception 
	 * @throws InvalidDefinition 
	 * @throws DependencyException 
	 * @throws NotFoundException 
	 * @throws NotFoundExceptionInterface 
	 * @throws ContainerExceptionInterface 
	 */
	public static function getByType(Type $type): array {
		if ($type->getID() === null)
			return [];
		$result = app()->db()->mysqli()->query("SELECT * FROM `e_{$type->getID()}`");
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$entity = new self;
			$entity->id = is_numeric($row['id']) ? +$row['id'] : $row['id'];
			$entity->type = $type;
			foreach ($row as $colName => $colValue) {
				$prop = $type->getPropByName($colName);
				if (!$prop)
					continue;
				$entity->properties[$colName] = match ($prop->getType()) {
					Prop::TYPE_BOOLEAN => !!+$colValue,
					Prop::TYPE_NUMBER => +$colValue,
					Prop::TYPE_JSON => json_decode($colValue, false, 512, JSON_UNESCAPED_UNICODE),
					default => $colValue
				};
			}
			$data[] = $entity;
		}
		$result->free();
		return $data;
	}

	public static function get(Type $type, int $id): ?self {
		$entityArray = self::getByType($type);
		foreach ($entityArray as $entity)
			if ($entity->id === $id)
				return $entity;
		return null;
	}
}
