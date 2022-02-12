<?= $this->include('include.begin') ?>
<main class="d-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
				<? if ($this->vars->error): ?>
					<p class="alert alert-danger"><?= $this->vars->error->message ?></p>
				<? endif ?>
			</div>
		</div>
	</div>
</main>
<?= $this->include('include.end') ?>