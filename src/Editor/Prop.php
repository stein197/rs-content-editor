<?php

namespace App\Editor;

use stdClass;
use function App\app;
use function App\array2object;

final class Prop {

	public const TYPE_BOOLEAN = 0;
	public const TYPE_NUMBER = 1;
	public const TYPE_STRING = 2;
	public const TYPE_JSON = 3;
	public const TYPE_DATE = 4;
	public const TYPE_FILE = 5;
	public const TYPE_ENTITY = 6;

	private ?int $id = null;

	public function __construct(private string $name, private int $type = self::TYPE_STRING, private bool $required = false, private ?string $format = null) {}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function setType(int $type): void {
		$this->type = $type;
	}

	public function setRequired(bool $required): void {
		$this->required = $required;
	}

	public function setFormat(?string $format): void {
		$this->format = $format;
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

	public function getType(): int {
		return $this->type;
	}

	public function getTypeAsString(): string {
		return match ($this->type) {
			self::TYPE_BOOLEAN => 'boolean',
			self::TYPE_NUMBER => 'number',
			self::TYPE_STRING => 'string',
			self::TYPE_JSON => 'json',
			self::TYPE_DATE => 'date',
			self::TYPE_FILE => 'file',
			self::TYPE_ENTITY => 'entity',
			default => 'string'
		};
	}

	public function getTypeAsSQL(): string {
		return match ($this->type) {
			self::TYPE_BOOLEAN => 'TINYINT(1)',
			self::TYPE_NUMBER => 'BIGINT',
			self::TYPE_STRING => 'TEXT',
			self::TYPE_JSON => 'JSON',
			self::TYPE_DATE => 'DATE',
			self::TYPE_FILE => 'TINYTEXT',
			self::TYPE_ENTITY => 'TINYTEXT',
			default => 'TEXT'
		};
	}

	public function getRequired(): bool {
		return $this->required;
	}

	public function getRequiredAsInt(): int {
		return +$this->required;
	}

	public function getRequiredAsSQL(): string {
		return $this->required ? 'NOT NULL' : 'NULL DEFAULT NULL';
	}

	public function getFormat(): ?string {
		return $this->format;
	}

	public function save(): void {
		if ($this->id === null) {
			$query = "INSERT INTO `entity_props` (`name`, `type`, `required`, `format`) VALUES ('{$this->getNameEscaped()}', '{$this->getTypeAsString()}', {$this->getRequiredAsInt()}, ".($this->format ? '\''.app()->db()->escape($this->format).'\'' : 'NULL').")";
			$mysqli = app()->db()->mysqli();
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
		} else {
			$querySet = [
				"`name` = '{$this->getNameEscaped()}'",
				"`type` = '{$this->getTypeAsString()}'",
				"`required` = {$this->getRequiredAsInt()}",
				"`format` = ".($this->format ? '\''.app()->db()->escape($this->format).'\'' : 'NULL')
			];
			$query = 'UPDATE `entity_props` SET '.join(', ', $querySet)." WHERE `id` = {$this->id}";
			app()->db()->mysqli()->query($query);
		}
	}

	public function delete(): void {
		app()->db()->mysqli()->query("DELETE FROM `entity_props` WHERE `id` = {$this->id}");
		$this->id = null;
	}

	public static function getByID(int $id): ?self {
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_props` WHERE `id` = ${id}");
		$data = $result->fetch_object();
		$result->free();
		return $data ? self::fromRecord($data) : null;
	}

	private static function fromRecord(stdClass | array $data): self {
		$data = $data instanceof stdClass ? $data : array2object($data);
		$result = new self($data->name, self::stringType2Const($data->type), !!+$data->required, $data->format ?: null);
		$result->id = $data->id;
		return $result;
	}

	public static function stringType2Const(string $type): int {
		return match($type) {
			'boolean' => self::TYPE_BOOLEAN,
			'number' => self::TYPE_NUMBER,
			'string' => self::TYPE_STRING,
			'json' => self::TYPE_JSON,
			'date' => self::TYPE_DATE,
			'file' => self::TYPE_FILE,
			'entity' => self::TYPE_ENTITY,
			default => self::TYPE_STRING
		};
	}
}
