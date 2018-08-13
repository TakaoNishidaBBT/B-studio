<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	function getRandomText($length) {
		$base = 'abcdefghijkmnprstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ2345678';
		for($i=0; $i<$length; $i++) {
			$pwd.= $base{mt_rand(0, strlen($base)-1)};
		}
		return $pwd;
	}

	// Set TERMINAL_ID
	if($_REQUEST['terminal_id']) {
		define('TERMINAL_ID', $_REQUEST['terminal_id']);
	}
	else {
		define('TERMINAL_ID', getRandomText(12));
	}

	// Set DISPATCH_URL
	define('DISPATCH_URL', 'index.php?terminal_id=' . TERMINAL_ID);

	require_once('./controller/controller.php');
