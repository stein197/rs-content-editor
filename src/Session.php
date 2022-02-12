<?php

namespace App;

use stdClass;

/**
 * @property stdClass $vars
 * @property int $varsHits
 * @package App
 */
final class Session {

	use JsonAccess;

	public function start(): void {
		session_start();
		$this->data = array2object($_SESSION);
	}

	public function destroy(): void {
		$this->data = null;
		session_destroy();
	}

	public function save(): bool {
		global $_SESSION;
		$_SESSION = object2array($this->data);
		return true;
	}
}
