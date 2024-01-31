<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// global function

	function __getRandomText($length) {
		$pwd = '';

		$base = 'abcdefghijkmnprstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ2345678';
		for($i=0; $i<$length; $i++) {
			$pwd.= $base[mt_rand(0, strlen($base)-1)];
		}
		return $pwd;
	}

	function __getPath() {
		$args = array();

		$func_array = func_get_args();
		for($i=0; $i < count($func_array); $i++) {
			if($func_array[$i]) $args[] = $func_array[$i];
		}
		return preg_replace('/(?<!:)\/+/', '/', implode('/', array_filter($args, 'strlen')));
	}

	function __($text) {
		if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') return $text;

		global $texts;

		return isset($texts[$text]) ? $texts[$text] : $text;
	}

	// class auto loader
	spl_autoload_register(function($class_name) {
		$admin_dir = dirname(str_replace('\\' , '/', __DIR__));

		$file_path = $admin_dir . '/class/' . $class_name . '.php';
		if(file_exists($file_path)) {
			require_once($file_path);
		}
	});

