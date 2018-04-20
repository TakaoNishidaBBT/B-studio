<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
//	require_once('../bs-admin/config/config.php');
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);
	ini_set('display_errors', 'On');
	set_error_handler('exception_error_handler');

	require_once('../bs-admin/config/config.php');
	require_once('../bs-admin/language/language.php');

	if($_POST['action'] == 'replace') {
		replace_view($error_message);
		$path = 'complete.php';
		header("Location:$path");
		exit;
	}

	// Send HTTP header
	header('Cache-Control: no-cache, must-revalidate'); 
	header('Content-Language: ' . $_SESSION['language']);
	header('Content-Type: text/html; charset=UTF-8');

	// Show HTML
	include('./view/view_replace.php');
	exit;

	function replace_view(&$error_message) {
		require_once('db_install.php');
		$db_install = new db_install();
		$db_install->createViews();
	}

	function exception_error_handler($errno, $errstr, $errfile, $errline) {
		if(!(error_reporting() & $errno)) {
			// error_reporting, unexpected error has occurred
			return;
		}

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
