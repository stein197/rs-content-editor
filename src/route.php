<?php
return function (Klein\Klein $klein): void {
	$klein->get('/', function () {
		return 'Klein';
	});
};
