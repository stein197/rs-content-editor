<?= $this->include('include.begin') ?>
<main class="d-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-md-6 col-lg-4 col-xl-3">
				<? if ($this->vars->error): ?>
					<p class="alert alert-danger"><?= $this->vars->error->message ?></p>
				<? endif ?>
				<div class="card">
					<div class="card-body">
						<form action="" method="POST">
							<? foreach ($this->vars->fields as $field): ?>
								<div class="form-group mb-2">
									<p class="fw-bold mb-1"><?= $field->label ?></p>
									<input type="<?= $field->type ?? 'text' ?>" required class="form-control" placeholder="<?= $field->label ?>" name="<?= $field->name ?>"/>
								</div>
							<? endforeach ?>
							<button class="btn btn-primary w-100">Далее</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?= $this->include('include.end') ?>
