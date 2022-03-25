<?php

namespace App\Editor;

use function App\app;

class User {

	public array $properties;
	private ?int $id = null;

	public function __construct(array $properties = []) {
		$this->properties = $properties;
	}

	public function save(): void {
		if ($this->id) {
			$name = app()->db()->escape($this->properties['name']);
			$pwHash = password_hash($this->properties['password'], PASSWORD_DEFAULT);
			app()->db()->mysqli()->query("UPDATE `users` SET `name` = '{$name}', `password` = '{$pwHash}', `admin` = {$this->properties['admin']} WHERE `id` = {$this->id}");
		} else {
			$name = app()->db()->escape($this->properties['name']);
			$pwHash = password_hash($this->properties['password'], PASSWORD_DEFAULT);
			$admin = +$this->properties['admin'];
			app()->db()->mysqli()->query("INSERT INTO `users` (`name`, `password`, `admin`) VALUE ('{$name}', '{$pwHash}', {$admin})");
			$this->id = +app()->db()->mysqli()->insert_id;
		}
	}

	public function delete(): void {
		if (!$this->id)
			return;
		app()->db()->mysqli()->query("DELETE FROM `users` WHERE `id` = {$this->id}");
	}

	public static function getByID(int $id): ?self {
		return self::getBy('id', $id);
	}

	public static function getByName(string $name): ?self {
		return self::getBy('name', $name);
	}

	private static function getBy(string $key, $value): ?self {
		if (!$value)
			return null;
		$value = app()->db()->escape($value);
		$data = app()->db()->mysqli()->query("SELECT * FROM `users` WHERE `{$key}` = '{$value}'")->fetch_array(MYSQLI_ASSOC);
		if (!$data)
			return null;
		foreach ($data as &$value)
			$value = is_numeric($value) ? +$value : $value;
		$user = new self($data);
		$user->id = +$data['id'];
		return $user;
	}
}
