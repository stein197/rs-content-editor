<?php

namespace App;

use mysqli;
use mysqli_sql_exception;
use Psr\Container\ContainerInterface;

final class Application {

	public function __construct(private ContainerInterface $container) {}

	public function container(): ContainerInterface {
		return $this->container;
	}

	public function init(): void {
		$this->config()->load();
	}

	public function config(): Config {
		return $this->container->get(Config::class);
	}

	public function db(): ?mysqli {
		try {
			return $this->container->get(mysqli::class);
		} catch (mysqli_sql_exception) {
			return null;
		}
	}

	// TODO
	public function install(): void {
		if ($this->isInstalled())
			return;
	}

	// TODO
	public function isInstalled(): bool {
		if (!$this->db())
			return false;
	}
}
