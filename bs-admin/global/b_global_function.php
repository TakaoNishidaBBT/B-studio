<?php
/*
 * B-square : Event Management and Registration System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
*/
	// global function

	function __getRandomText($length) {
		$base = 'abcdefghijkmnprstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ2345678';
		for($i=0; $i<$length; $i++) {
			$pwd.= $base{mt_rand(0, strlen($base)-1)};
		}
		return $pwd;
	}

	function __getPath() {
		$path = str_replace('\\', '/', func_get_arg(0));
		for($i=1; $i<func_num_args(); $i++) {
			if(!$file_name = func_get_arg($i)) continue;
			if(substr($path, -1) == '/') {
				$path = substr($path, 0, -1);
			}
			if(substr($file_name, 0, 1) == '/') {
				$file_name = substr($file_name, 1);
			}
			if($path) $path.= '/';

			$path.= $file_name;
		}
		return $path;
	}

	function __($text) {
		if($_SESSION['language'] == 'en') return $text;

		global $texts;

		return $texts[$text] ? $texts[$text] : $text;
	}

	// class auto loader
	spl_autoload_register(function($class_name) {
		$admin_dir = dirname(str_replace('\\' , '/', __DIR__));

		$file_path = $admin_dir . '/class/' . $class_name . '.php';
		if(file_exists($file_path)) {
			require_once($file_path);
		}
	});

