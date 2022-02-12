<?= $this->include('include.begin') ?>
<?= $this->include('form', [
	'error' => $this->vars->error,
	'button' => 'Подключиться и установить',
	'title' => 'Установка и подключение к базе данных',
	'action' => App\route('install'),
	'fields' => [
		[
			'label' => 'Хост',
			'name' => 'db[host]',
			'default' => 'localhost',
			'required' => true
		],
		[
			'label' => 'Пользователь',
			'name' => 'db[user]',
			'default' => 'root',
			'required' => true
		],
		[
			'label' => 'Пароль',
			'name' => 'db[password]',
			'type' => 'password'
		],
		[
			'label' => 'Имя базы данных',
			'name' => 'db[name]',
			'required' => true
		]
	]
]) ?>
<?= $this->include('include.end') ?>
