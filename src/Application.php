<?php

namespace App;

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

	public function db(): ?Database {
		try {
			return $this->container->get(Database::class);
		} catch (mysqli_sql_exception) {
			return null;
		}
	}
}
