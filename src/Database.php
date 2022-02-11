<?php

namespace App;

use mysqli;

final class Database {

	public const CREDENTIALS_CONFIG = [
		[
			'label' => 'Хост',
			'name' => 'host',
			'formName' => 'db[host]',
			'default' => 'localhost',
			'required' => true
		],
		[
			'label' => 'Пользователь',
			'name' => 'user',
			'formName' => 'db[user]',
			'default' => 'root',
			'required' => true
		],
		[
			'label' => 'Пароль',
			'name' => 'password',
			'formName' => 'db[password]',
			'type' => 'password'
		],
		[
			'label' => 'Имя базы данных',
			'name' => 'name',
			'formName' => 'db[name]'
		]
	];

	public function __construct(private mysqli $mysqli) {}

	public function mysqli(): mysqli {
		return $this->mysqli;
	}
}