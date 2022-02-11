<? if ($this->vars->title): ?>
	<p class="fs-6 fw-bold text-center"><?= $this->vars->title ?></p>
<? endif ?>
<? if ($this->vars->error): ?>
	<p class="alert alert-danger"><?= $this->vars->error->message ?></p>
<? endif ?>
<div class="card">
	<div class="card-body">
		<form action="<?= $this->vars->action ?>" method="POST">
			<? foreach ($this->vars->fields as $field): ?>
				<div class="form-group mb-2">
					<p class="fw-bold mb-1"><?= $field->label ?></p>
					<input type="<?= $field->type ?? 'text' ?>" <?= $field->required ? 'required' : '' ?> class="form-control" placeholder="<?= $field->label ?>" name="<?= $field->name ?>" value="<?= $field->default ?>"/>
				</div>
			<? endforeach ?>
			<button class="btn btn-primary w-100"><?= $this->vars->button ?? 'Далее' ?></button>
		</form>
	</div>
</div>
