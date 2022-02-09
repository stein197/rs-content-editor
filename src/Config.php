<?php
namespace App;

use ParseError;
use stdClass;

final class Config {

	private ?stdClass $data;

	public function __construct(private string $path) {}

	/**
	 * Загружает файл и парсит его.
	 * @throws ParseError При ошибке парсинка JSON-файла.
	 */
	public function load(): void {
		if (file_exists($this->path)) {
			$this->data = json_decode(file_get_contents($this->path));
			if ($this->data === null)
				throw new ParseError("Failed to parse {$this->path} file");
		} else {
			file_put_contents($this->path, '{}');
			$this->data = new stdClass();
		}
	}

	public function save(): bool {
		return !!file_put_contents($this->path, json_encode($this->data, JSON_PRETTY_PRINT));
	}

	public function __get(string $name): mixed {
		return $this->data->{$name};
	}
}
