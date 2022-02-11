<?= $this->include('include.begin') ?>
<main class="d-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-md-6 col-lg-4 col-xl-3">
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
			</div>
		</div>
	</div>
</main>
<?= $this->include('include.end') ?>
