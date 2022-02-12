<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="stylesheet" href="/index.css?<?= filemtime(App\resolvePath('public/index.css')) ?>"/>
		<? if ($this->vars->index): ?>
			<script src="/index.js?<?= filemtime(App\resolvePath('public/index.js')) ?>" defer="true"></script>
		<? endif ?>
	</head>
	<body>
