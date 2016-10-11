<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);
	ini_set('display_errors','Off');
	set_error_handler('exception_error_handler');

	// CHARSET
	define('B_CHARSET', 'UTF-8');
	mb_internal_encoding(B_CHARSET);

	// start session
	require_once('../bs-admin/class/B_Session.php');
	$info = pathinfo($_SERVER['SCRIPT_NAME']);
	define('SESSION_DIR', $info['dirname']);

	$ses = new B_Session;
	$ses->start('nocache', 'bs-install', SESSION_DIR);
	$ses->end();

	// Send HTTP header
	header('Cache-Control: no-cache, must-revalidate'); 
	header('Content-Language: ja');
	header('Content-Type: text/html; charset=UTF-8');

	// show HTML
	include('./view/view_complete.php');

	function exception_error_handler($errno, $errstr, $errfile, $errline) {
		if(!(error_reporting() & $errno)) {
			// error_reporting, unexpected error has occurred
			return;
		}

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
