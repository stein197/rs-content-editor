<?php

return function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', 'Index');
};
