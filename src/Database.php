<?php

namespace App;

use stdClass;
use mysqli;
use function password_hash;
use const PASSWORD_DEFAULT;
use function is_numeric;
use const MYSQLI_ASSOC;

final class Database {

	public function __construct(private mysqli $mysqli) {}

	public function mysqli(): mysqli {
		return $this->mysqli;
	}

	public function createAdminUser(string $name, string $password): void {
		$pwHash = password_hash($password, PASSWORD_DEFAULT);
		$this->mysqli->query("INSERT INTO `users` (`name`, `password`, `admin`) VALUES ('{$this->escape($name)}', '{$pwHash}', 1)");
	}

	public function hasAdminUser(): bool {
		$result = $this->mysqli->query('SELECT * FROM `users` WHERE `admin`');
		$amount = sizeof($result->fetch_all());
		$result->free();
		return $amount > 0;
	}

	public function getUsers(): array {
		$result = $this->mysqli->query('SELECT * FROM `users`');
		$list = $result->fetch_all(MYSQLI_ASSOC);
		$result->free();
		return $list;
	}

	public function getUserByName(string $name): ?stdClass {
		$result = $this->mysqli->query("SELECT * FROM `users` WHERE `name` = '{$this->escape($name)}'");
		$user = $result->fetch_object();
		$result->free();
		if ($user)
			foreach ($user as $key => &$value)
				$value = is_numeric($value) ? +$value : $value;
		return $user ?: null;
	}

	public function escape(string $string): string {
		return $this->mysqli->real_escape_string($string);
	}
}
