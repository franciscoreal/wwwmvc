<?php

spl_autoload_register(function ($class){
	$appDir = __DIR__;
	$path = [
		'controllers',
		'core',
		'models'
	];
	$path = array_map(function($p) use($appDir, $class) {
		return sprintf('%s/%s/%s.php', $appDir, $p, strtolower($class));
	}, $path);

	$is_file = array_map('is_file', $path);
	$index = array_keys($is_file, true);

	if (isset($index[0])) {
		require_once $path[$index[0]];
	}
});