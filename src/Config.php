<?php
namespace App;

use ParseError;
use stdClass;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;

final class Config {

	use JsonAccess;

	public function __construct(private string $path) {}

	/**
	 * Загружает файл и парсит его.
	 * @throws ParseError При ошибке парсинка JSON-файла.
	 */
	public function load(): void {
		if (file_exists($this->path)) {
			$this->data = json_decode(file_get_contents($this->path), false, 512, JSON_UNESCAPED_UNICODE);
			if ($this->data === null)
				throw new ParseError("Failed to parse {$this->path} file");
		} else {
			file_put_contents($this->path, '{}');
			$this->data = new stdClass();
		}
	}

	public function save(): bool {
		return !!file_put_contents($this->path, json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}
}
