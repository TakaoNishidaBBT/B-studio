<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	require_once('./config/config.php');
	require_once('./module/common/B_AdminModule.php');

	mb_language('Japanese');

	// セッションスタート
	$ses = new B_Session;
	$ses->start('nocache', B_ADMIN_SESSION_NAME, B_CURRENT_ROOT);

	$controller = new B_Controller;
	$auth = new B_AdminAuth;

	try {
		if(!isset($_REQUEST['terminal_id']) || !$auth->checkUserAuth()) {
			throw new Exception();
		}

		// TERMINAL_ID設定
		define('TERMINAL_ID', $_REQUEST['terminal_id']);
		define("DISPATCH_URL", 'index.php?terminal_id=' . TERMINAL_ID);

		// 起動モジュール設定
		$module_dir = 'module/';
		$page = $_REQUEST['page'] . '.php';
		$dir = $module_dir . $_REQUEST['module'];
		$file_name = $dir . '/' . $page;

		if(!file_exists($file_name)) {
			throw new Exception();
		}
		$class = $_REQUEST['module'] . "_" . $_REQUEST['page'];
		$method = "func_default";
		if(isset($_REQUEST['method'])) {
			$method = $_REQUEST['method'];
		}
	}
	catch (Exception $e) {
		$module_dir = 'module/';
		$dir = $module_dir . 'index';
		$page = 'index.php';
		$class = 'index_index';
	}

	$controller->dispatch($dir, $page, $class, $method);
