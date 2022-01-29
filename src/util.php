<?php
namespace App;

use DI\Container;
use DI\ContainerBuilder;

function container(): Container {
	static $container;
	if (!$container) {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'definition.php');
		$builder->useAutowiring(false);
		$container = $builder->build();
	}
	return $container;
}
