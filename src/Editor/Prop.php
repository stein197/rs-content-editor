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

	private ?int $id = null;

	public function __construct(private string $name, private int $type = self::TYPE_STRING, private bool $required = false) {}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function setType(int $type): void {
		$this->type = $type;
	}

	public function setRequired(bool $required): void {
		$this->required = $required;
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
		};
	}

	public function getTypeAsSQL(): string {
		return match ($this->type) {
			self::TYPE_BOOLEAN => 'TINYINT(1)',
			self::TYPE_NUMBER => 'BIGINT',
			self::TYPE_STRING => 'TEXT',
			self::TYPE_JSON => 'JSON',
			self::TYPE_DATE => 'DATE',
			self::TYPE_FILE => 'TINYTEXT'
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

	public function save(): void {
		if ($this->id === null) {
			$query = "INSERT INTO `entity_props` (`name`, `type`, `required`) VALUES ('{$this->getNameEscaped()}', '{$this->getTypeAsString()}', {$this->getRequiredAsInt()})";
			$mysqli = app()->db()->mysqli();
			$mysqli->query($query);
			$this->id = (int) $mysqli->insert_id;
		} else {
			$querySet = [
				"`name` = '{$this->getNameEscaped()}'",
				"`type` = '{$this->getTypeAsString()}'",
				"`required` = {$this->getRequiredAsInt()}"
			];
			$query = 'UPDATE `entity_props` SET '.join(', ', $querySet)." WHERE `id` = {$this->id}";
			app()->db()->mysqli()->query($query);
		}
	}

	public static function getByID(int $id): ?self {
		$result = app()->db()->mysqli()->query("SELECT * FROM `entity_props` WHERE `id` = ${id}");
		$data = $result->fetch_object();
		$result->free();
		return $data ? self::fromRecord($data) : null;
	}

	private static function fromRecord(stdClass | array $data): self {
		$data = $data instanceof stdClass ? $data : array2object($data);
		$result = new self($data->name, self::sqlType2Const($data->type), !!+$data->required);
		$result->id = $data->id;
		return $result;
	}

	private static function sqlType2Const(string $type): int {
		return match($type) {
			'boolean' => self::TYPE_BOOLEAN,
			'number' => self::TYPE_NUMBER,
			'string' => self::TYPE_STRING,
			'json' => self::TYPE_JSON,
			'date' => self::TYPE_DATE,
			'file' => self::TYPE_FILE,
			default => self::TYPE_STRING
		};
	}
}
