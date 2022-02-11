<?php

namespace App;

final class Installer {

	public function __construct(private Application $app) {}


	// TODO
	public function install(): void {
		if ($this->installed())
			return;
	}

	// TODO
	public function installed(): bool {
		return !!$this->app->db();
	}
}
