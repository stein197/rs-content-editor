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
use stdClass;
use function App\app;
use function App\array2object;
use function json_encode;
use function json_decode;
use const MYSQLI_ASSOC;

final class Type {

	private ?int $id = null;
	private ?stdClass $properties = null;
	private ?self $parent = null;
	private ?int $parentID = null;

	public function __construct(private string $name) {}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function setProperties(stdClass $properties): void {
		$this->properties = $properties;
	}

	public function setParent(self | int $parent): void {
		if ($parent instanceof self) {
			$this->parent = $parent;
			$this->parentID = $parent->id;
		} else {
			$this->parentID = $parent;
		}
	}

	public function getID(): ?int {
		return $this->id;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getProperties(): ?stdClass {
		return $this->properties;
	}

	public function getParent(): ?self {
		return $this->parentID === null ? null : $this->parent = self::get($this->parentID);
	}

	public function save(): void {
		if ($this->id === null) {
			$name = app()->db()->escape($this->name);
			$properties = json_encode($this->properties);
			$parent = $this->parentID === null ? 0 : $this->parentID;
			$query = "INSERT INTO `entity_types` (`name`, `properties`, `parent`) VALUES ('{$name}', '{$properties}', {$parent})";
			$mysqli = app()->db()->mysqli();
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
			app()->db()->mysqli()->query("CREATE TABLE `e_{$this->id}` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB");
		} else {
			$querySet = [
				'`name` = '.app()->db()->escape($this->name),
				'`properties` = '.json_encode($this->properties),
				'`parent` = '.$this->parentID
			];
			$query = 'UPDATE `entity_types` SET '.join(', ', $querySet)." WHERE `id` = {$this->id}";
			app()->db()->mysqli()->query($query);
		}
	}

	public static function get(int $id): ?self {
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_types` WHERE `id` = {$id}");
		$data = $result->fetch_object();
		$result->free();
		return $data ? self::fromRecord($data) : null;
	}

	/**
	 * 
	 * @param int $id Parent id.
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
	public static function getByParentID(int $id): array {
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_types` WHERE `parent` = {$id}");
		$data = [];
		while ($row = $result->fetch_object())
			$data[] = self::fromRecord($row);
		$result->free();
		return $data;
	}

	private static function fromRecord(array | stdClass $data): self {
		$data = $data instanceof stdClass ? $data : array2object($data);
		$result = new self($data->name);
		$result->id = $data->id;
		$result->properties = json_decode($data->properties);
		$result->parentID = (int) $result->parent;
		return $result;
	}
}
