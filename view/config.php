<?= $this->include('include.begin') ?>
<main class="d-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-md-6 col-lg-4 col-xl-3">
				<div class="card">
					<div class="card-body">
						<form action="" method="POST">
							<div class="form-group mb-2">
								<p class="fw-bold mb-1">Хост</p>
								<input type="text" class="form-control" placeholder="Хост" name="host"/>
							</div>
							<div class="form-group mb-2">
								<p class="fw-bold mb-1">Пользователь</p>
								<input type="text" class="form-control" placeholder="Пользователь" name="user"/>
							</div>
							<div class="form-group mb-2">
								<p class="fw-bold mb-1">Пароль</p>
								<input type="password" class="form-control" placeholder="Пароль" name="password"/>
							</div>
							<div class="form-group">
								<p class="fw-bold mb-1">БД</p>
								<input type="text" class="form-control" placeholder="БД" name="name"/>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?= $this->include('include.end') ?>
