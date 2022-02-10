<?php

namespace App;

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
}
