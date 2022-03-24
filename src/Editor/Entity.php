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
use function App\resolvePath;
use function is_numeric;
use function date_create_from_format;
use function date;
use function file_exists;
use function mkdir;
use function file_put_contents;
use function base64_decode;
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
				$prop = $this->type->getPropByName($propName);
				$insertClause[] = "`{$propName}`";
				$valueClause[] = match (gettype($propValue)) {
					'boolean', 'integer', 'double' => +$propValue,
					'array' => '\''.app()->db()->escape(self::formatFileName($prop->getFormat() ?? '', $propValue['name'])).'\'',
					default => $propValue ? '\''.app()->db()->escape($propValue).'\'' : 'NULL'
				};
				if ($prop->getType() === Prop::TYPE_FILE)
					$this->saveFile($prop->getFormat(), $propValue);
			}
			$query = "INSERT INTO `e_{$this->type->getID()}` (".join(',', $insertClause).") VALUE (".join(',', $valueClause).")";
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
		} else {
			$setClause = [];
			foreach ($this->properties as $propName => $propValue) {
				$prop = $this->type->getPropByName($propName);
				$setClause[] = "`{$propName}` = ".match (gettype($propValue)) {
					'boolean', 'integer', 'double' => +$propValue,
					'array' => '\''.app()->db()->escape(self::formatFileName($prop->getFormat() ?? '', $propValue['name'])).'\'',
					default => $propValue ? '\''.app()->db()->escape($propValue).'\'' : 'NULL'
				};
				if ($prop->getType() === Prop::TYPE_FILE)
					$this->saveFile($prop->getFormat(), $propValue);
			}
			$query = "UPDATE `e_{$this->type->getID()}` SET ".join(', ', $setClause)." WHERE `id` = '{$this->id}'";
			$mysqli->query($query);
		}
	}

	private function saveFile(string $format, array $file): void {
		$dir = self::parseFormat($format)['dir'];
		if (!file_exists($dir))
			mkdir($dir, 0777, true);
		file_put_contents("{$dir}/{$file['name']}", base64_decode($file['data']));
	}

	private static function parseFormat(string $format): array {
		[$dir, $storeFormat, $displayFormat] = explode(';', $format);
		$dir = resolvePath('public/'.($dir ?: '/media'));
		return [
			'dir' => $dir,
			'storeFormat' => $storeFormat ?: '{name}.{ext}',
			'displayFormat' => $displayFormat ?: '{name}.{ext}'
		];
	}

	private static function formatFileName(string $format, string $fileName): string {
		$displayFormat = self::parseFormat($format)['displayFormat'];
		[$fileName, $fileExt] = explode('.', $fileName);
		return str_replace('{ext}', $fileExt, str_replace('{name}', $fileName, $displayFormat));
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
