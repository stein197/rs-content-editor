<?php
	namespace App;

	use DI\Container;
	use DI\ContainerBuilder;

	function container(): Container {
		static $container;
		if (!$container) {
			$builder = new ContainerBuilder();
			$builder->addDefinitions('definition.php');
			$container = $builder->build();
		}
		return $container;
	}
