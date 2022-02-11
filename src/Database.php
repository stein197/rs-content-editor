<?php

namespace App;

use mysqli;

final class Database {

	public function __construct(private mysqli $mysqli) {}

	public function mysqli(): mysqli {
		return $this->mysqli;
	}

	// TODO
	public function hasAdminUser(): bool {
		return false;
	}
}