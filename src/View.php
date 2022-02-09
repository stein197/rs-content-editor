<?php
namespace App;

use Exception;
use stdClass;
final class View {

	private string $path;
	private stdClass $vars;

	public function __construct(private string $name, array $vars = []) {
		$this->path = resolvePath('View/'.str_replace('.', DIRECTORY_SEPARATOR, $name).'.php');
		$this->vars = array2object($vars);
	}

	public function render(): string {
		ob_start();
		require $this->path;
		return ob_get_clean();
	}

	protected function include(string $name, array $vars = []): string {
		try {
			return (new self($name, array_merge((array) $this->vars, $vars)))->render();
		} catch (Exception $ex) {
			return $ex->getMessage();
		}
	}
}
