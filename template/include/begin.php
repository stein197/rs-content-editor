<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/index.css"/>
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
