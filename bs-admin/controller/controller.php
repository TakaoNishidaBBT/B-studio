<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	require_once('./module/common/B_AdminModule.php');

	$controller = new B_Controller;
	$auth = new B_AdminAuth;

	try {
		if(!isset($_REQUEST['terminal_id']) || !$auth->checkUserAuth()) {
			throw new Exception();
		}

		$module_dir = 'module/';
		$page = $_REQUEST['page'] . '.php';
		$dir = $module_dir . $_REQUEST['module'];
		$file_name = $dir . '/' . $page;

		if(!file_exists($file_name)) {
			throw new Exception();
		}
		$class = $_REQUEST['module'] . '_' . $_REQUEST['page'];
		$method = 'func_default';
		if(isset($_REQUEST['method'])) {
			$method = $_REQUEST['method'];
		}
	}
	catch(Exception $e) {
		$module_dir = 'module/';
		$dir = $module_dir . 'index';
		$page = 'index.php';
		$class = 'index_index';
	}

	// Dispatch
	$controller->dispatch($dir, $page, $class, $method);
