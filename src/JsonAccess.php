<?php

namespace App;

use stdClass;

trait JsonAccess {

	protected ?stdClass $data;

	public function __get(string $name): mixed {
		return $this->data->{$name};
	}

	public function __set(string $name, mixed $value): void {
		$this->data->{$name} = $value;
	}

	public function __unset(string $name): void {
		unset($this->data->{$name});
	}

	abstract public function save(): bool;
}
