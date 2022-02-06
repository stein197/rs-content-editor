<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<? if ($this->vars->static): ?>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"/>
		<? endif ?>
		<? if ($this->vars->spa): ?>
			<script src="/index.js" defer="true"></script>
		<? endif ?>
		<style>
			html,
			body {
				min-height: 100vh;
			}
		</style>
	</head>
	<body>
