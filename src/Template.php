<?php
namespace App;

final class Template {

	private string $path;
	private object $vars;

	public function __construct(private string $name, ?array $vars) {
		$this->path = resolvePath('template/'.str_replace('.', DIRECTORY_SEPARATOR, $name).'.php');
		$this->vars = (object) $vars;
	}

	public function render(): string {
		ob_start();
		require $this->path;
		return ob_get_clean();
	}

	protected function include(string $name, ?array $vars = []): string {
		return (new self($name, array_merge((array) $this->vars, $vars)))->render();
	}
}
