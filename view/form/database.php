<?= $this->include('include.begin') ?>
<main class="d-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-md-6 col-lg-4 col-xl-3">
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
							'name' => 'db[name]'
						]
					]
				]) ?>
			</div>
		</div>
	</div>
</main>
<?= $this->include('include.end') ?>
