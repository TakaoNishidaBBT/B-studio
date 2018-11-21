<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	require_once('global/b_global_function.php');

	// Set TERMINAL_ID
	if($_REQUEST['terminal_id']) {
		define('TERMINAL_ID', $_REQUEST['terminal_id']);
	}
	else {
		define('TERMINAL_ID', __getRandomText(12));
	}

	// Set DISPATCH_URL
	define('DISPATCH_URL', 'index.php?terminal_id=' . TERMINAL_ID);

	// Set directory information
	$current_dir = dirname(str_replace('\\' , '/', __DIR__));
	$doc_root = str_replace('\\' , '/', realpath($_SERVER['DOCUMENT_ROOT']));
	if(substr($doc_root, -1) != '/') {
		$doc_root.= '/';
	}
	$current_path = str_replace(strtolower($doc_root), '', strtolower($current_dir));
	$current = basename($current_dir);

	if(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] !== 'on') {
		$session_secure = false;
	}
	else {
		$session_secure = true;
	}

	// Start admin session
	$ses = new B_Session;
	$ses->start('nocache', $current . '-admin-session', '/' . $current_path . '/', $session_secure);

	require_once('./controller/controller.php');
