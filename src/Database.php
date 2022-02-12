<?php

namespace App;

use mysqli;
use function password_hash;
use const PASSWORD_DEFAULT;

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

	public function escape(string $string): string {
		return $this->mysqli->real_escape_string($string);
	}
}
