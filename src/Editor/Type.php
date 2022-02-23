<?php

namespace App\Editor;

use stdClass;
use LogicException;
use InvalidArgumentException;
use Exception;
use DI\Definition\Exception\InvalidDefinition;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use function App\app;
use function App\array2object;
use function json_encode;
use function json_decode;
use const MYSQLI_ASSOC;
use const JSON_UNESCAPED_UNICODE;

final class Type {

	private ?int $id = null;
	private ?stdClass $properties = null;
	/** @var Prop[] */
	private array $props = [];
	private ?self $parent = null;
	private ?int $parentID = null;

	public function __construct(private string $name) {}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function setProperties(stdClass $properties): void {
		$this->properties = $properties;
	}

	public function setProperty(string $name, mixed $value): void {
		$this->properties = $this->properties ?? new stdClass;
		$this->properties->{$name} = $value;
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

	public function getNameEscaped(): string {
		return app()->db()->escape($this->name);
	}

	public function getProperties(): ?stdClass {
		return $this->properties;
	}

	public function getParent(): ?self {
		return $this->parentID === null ? null : $this->parent = self::get($this->parentID);
	}

	public function addProp(Prop $prop): void {
		$this->props[] = $prop;
	}

	public function hasProp(Prop $prop): bool {
		if ($prop->getID() === null || $this->id === null)
			return false;
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_types_props` WHERE `property_id` = {$prop->getID()} AND `type_id` = {$this->id}");
		$has = $result->num_rows > 0;
		$result->free();
		$result = app()->db()->mysqli()->query("SHOW COLUMNS FROM `e_{$this->id}` LIKE '{$prop->getNameEscaped()}'");
		$has = $has && $result->num_rows > 0;
		return $has;
	}

	/**
	 * @return Prop[]
	 */
	public function getProps(): array {
		return $this->props;
	}

	public function getPropByName(string $name): ?Prop {
		foreach ($this->props as $prop)
			if ($prop->getName() === $name)
				return $prop;
		return null;
	}

	/**
	 * @return Entity[]
	 */
	public function getEntities(): array {
		if ($this->id === null)
			return [];
		return Entity::getByType($this);
	}

	public function save(): void {
		$mysqli = app()->db()->mysqli();
		if ($this->id === null) {
			$name = app()->db()->escape($this->name);
			$properties = json_encode($this->properties, JSON_UNESCAPED_UNICODE);
			$parent = $this->parentID === null ? 0 : $this->parentID;
			$query = "INSERT INTO `entity_types` (`name`, `properties`, `parent`) VALUES ('{$name}', '{$properties}', {$parent})";
			$mysqli = app()->db()->mysqli();
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
			// TODO: id auto_increment and string id
			$mysqli->query("CREATE TABLE `e_{$this->id}` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE = InnoDB");
			// $mysqli->query("CREATE TABLE `e_{$this->id}` (`_` TINYINT(1) NULL DEFAULT NULL) ENGINE = InnoDB");
		} else {
			$querySet = [
				"`name` = '{$this->getNameEscaped()}'",
				'`properties` = '.($this->properties ? '\''.json_encode($this->properties, JSON_UNESCAPED_UNICODE).'\'' : 'NULL'),
				'`parent` = '.($this->parentID ?? 0)
			];
			$query = 'UPDATE `entity_types` SET '.join(', ', $querySet)." WHERE `id` = {$this->id}";
			$mysqli->query($query);
		}
		foreach ($this->props as $prop) {
			$prop->save();
			$mysqli->query("INSERT IGNORE INTO `entity_types_props` (`property_id`, `type_id`) VALUES ({$prop->getID()}, {$this->id})");
			try {
				if ($prop->getName() === "id") {
					$queryRs = $mysqli->query("SHOW COLUMNS FROM `e_{$this->id}` LIKE 'id'");
					$idRow = $queryRs->fetch_object();
					$queryRs->free();
					if ($prop->getType() === Prop::TYPE_NUMBER) {
						if (str_starts_with($idRow->Type, 'varchar')) {
							$mysqli->query("ALTER TABLE `e_{$this->id}` MODIFY COLUMN `id` INT UNSIGNED NOT NULL");
							$mysqli->query("DROP INDEX `PRIMARY` ON `e_{$this->id}`");
							$mysqli->query("ALTER TABLE `e_{$this->id}` MODIFY COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT");
							$mysqli->query("ALTER TABLE `e_{$this->id}` ADD PRIMARY KEY (`id`)");
						}
					} else {
						if (str_starts_with($idRow->Type, 'int')) {
							$mysqli->query("ALTER TABLE `e_{$this->id}` MODIFY COLUMN `id` INT");
							$mysqli->query("DROP INDEX `PRIMARY` ON `e_{$this->id}`");
							$mysqli->query("ALTER TABLE `e_{$this->id}` MODIFY COLUMN `id` VARCHAR(64) NOT NULL");
							$mysqli->query("ALTER TABLE `e_{$this->id}` ADD PRIMARY KEY (`id`)");
						}
					}
				} else {
					$query = "ALTER TABLE `e_{$this->id}` ".($this->hasProp($prop) ? 'MODIFY' : 'ADD')." `{$prop->getNameEscaped()}` {$prop->getTypeAsSQL()} {$prop->getRequiredAsSQL()}";
					$mysqli->query($query);
				}
			} catch (\Exception $ex) {
				$a = 1;
			}
		}
	}

	private function fetchProps(): void {
		if ($this->id === null)
			return;
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_types_props` WHERE `type_id` = {$this->id}");
		foreach ($result->fetch_all(MYSQLI_ASSOC) as $row)
			$this->props[] = Prop::getByID(+$row['property_id']);
		$result->free();
	}

	public static function get(int $id): ?self {
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_types` WHERE `id` = {$id}");
		$data = $result->fetch_object();
		$result->free();
		return $data ? self::fromRecord($data) : null;
	}

	/**
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
		$result->properties = json_decode($data->properties, false, 512, JSON_UNESCAPED_UNICODE);
		$result->parentID = (int) $result->parent;
		$result->fetchProps();
		return $result;
	}
}