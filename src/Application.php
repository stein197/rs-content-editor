<?php

namespace App;

use mysqli_sql_exception;
use Psr\Container\ContainerInterface;

final class Application {

	private Session $session;

	public function __construct(private ContainerInterface $container) {}

	public function container(): ContainerInterface {
		return $this->container;
	}

	public function init(): void {
		$this->session = $this->container->get(Session::class);
		$this->session->start();
		$this->config()->load();
		$this->refreshSessionViewVars();
	}

	public function session(): Session {
		return $this->session;
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

	public function terminate(): void {}

	private function refreshSessionViewVars(): void {
		if (!$this->session->vars)
			return;
		if ($this->session->varsHits > 1) {
			unset($this->session->varsHits);
			unset($this->session->vars);
		}
		if ($this->session->varsHits)
			$this->session->varsHits++;
		else
			$this->session->varsHits = 1;
		$this->session->save();
	}
}
