<?php

namespace App\Editor;

use function App\app;
use function is_numeric;

// TODO
final class Entity {

	private int | string | null $id = null;
	private array $properties = [];

	public function __construct() {}

	public function getProperties(): array {
		return $this->properties;
	}

	public static function getByType(Type $type): array {
		if ($type->getID() === null)
			return [];
		$result = app()->db()->mysqli()->query("SELECT * FROM `e_{$type->getID()}`");
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$entity = new self;
			$entity->id = is_numeric($row['id']) ? +$row['id'] : $row['id'];
			foreach ($row as $colName => $colValue) {
				$prop = $type->getPropByName($colName);
				if (!$prop)
					continue;
				$entity->properties[$colName] = match ($prop->getType()) {
					Prop::TYPE_BOOLEAN => !!+$colValue,
					Prop::TYPE_NUMBER => +$colValue,
					Prop::TYPE_JSON => json_decode($colValue, false, 512, \JSON_UNESCAPED_UNICODE),
					// Prop::TYPE_JSON => json_decode($colValue),
					default => $colValue
				};
			}
			$data[] = $entity;
		}
		$result->free();
		return $data;
	}
}