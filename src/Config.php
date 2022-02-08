<?php
namespace App;

use Exception;
use ParseError;
use stdClass;

final class Config {

	public ?object $data;

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

	public function get(string $query): mixed {
		$parsedQuery = self::parseQuery($query);
		return $this->getElementAt($parsedQuery, sizeof($parsedQuery) - 1);
	}

	public function set(string $query, mixed $value): void {
		$parsedQuery = self::parseQuery($query);
		$element = &$this->getElementAt($parsedQuery, sizeof($parsedQuery) - 2);
		$element->{$parsedQuery[sizeof($parsedQuery) - 1]} = $value;
	}

	private static function parseQuery(string $query): array {
		$result = [];
		$curPart = '';
		$curQuote = null;
		for ($i = 0; $i < mb_strlen($query); $i++) {
			$char = $query[$i];
			$prevChar = @$query[$i - 1];
			switch ($char) {
				case '.':
					if ($curQuote) {
						$curPart .= $char;
					} else {
						if ($curPart)
							$result[] = $curPart;
						$curPart = '';
					}
					break;
				case '[':
					if ($curQuote) {
						$curPart .= $char;
					} else {
						if ($curPart)
							$result[] = $curPart;
						$curPart = '';
					}
					break;
				case ']':
					if ($curQuote) {
						$curPart .= $char;
					}
					break;
				case '\'':
				case '"':
					if ($curQuote) {
						if ($curQuote === $char) {
							if ($prevChar === '\\') {
								$curPart .= $char;
							} else {
								$curQuote = null;
								$result[] = $curPart;
								$curPart = '';
							}
						} else {
							$curPart .= $char;
						}
					} else {
						$curQuote = $char;
					}
					break;
				default:
					$curPart .= $char;
			}
		}
		if ($curPart)
			$result[] = $curPart;
		return $result;
	}

	private function &getElementAt(array $parsedQuery, int $index): mixed {
		$curValue = &$this->data;
		for ($i = 0; $i < sizeof($parsedQuery) - ($index + 1); $i++)
			array_pop($parsedQuery);
		try {
			foreach ($parsedQuery as $part) {
				if ($curValue === null)
					break;
				if (is_int($part))
					$curValue = &$curValue[$part];
				else
					$curValue = &$curValue->{$part};
			}
			return $curValue;
		} catch (Exception) {
			return null;
		}
	}
}
