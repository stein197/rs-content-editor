<?php

use App\Controller\Index;

return function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', Index::class);
};
