<?php
namespace App;

final class Template {

	private string $path;
	private object $vars;
	private array $parts = [];

	public function __construct(private string $name, ?array $vars) {
		$this->path = resolvePath('template/'.str_replace('.', DIRECTORY_SEPARATOR, $name).'.php');
		$this->vars = (object) $vars;
	}

	public function render(): string {
		ob_start();
		require $this->path;
		$this->parts[] = ob_get_clean();
		return join('', $this->parts);
	}

	protected function include(string $name, ?array $vars = []) {
		$this->parts[] = ob_get_clean();
		$this->parts[] = (new self($name, array_merge((array) $this->vars, $vars)))->render();
		ob_start();
	}
}
