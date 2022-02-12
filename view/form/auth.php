<?= $this->include('include.begin') ?>
<?= $this->include('form', [
	'error' => $this->vars->error,
	'button' => $this->vars->button,
	'title' => $this->vars->title,
	'action' => $this->vars->action,
	'fields' => [
		[
			'label' => 'Имя',
			'name' => 'user[name]',
			'default' => 'admin',
			'required' => true
		],
		[
			'label' => 'Пароль',
			'name' => 'user[password]',
			'type' => 'password',
			'required' => true
		],
	]
]) ?>
<?= $this->include('include.end') ?>
