<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	require_once('./bs-admin/config/config.php');
	require_once('./bs-admin/module/common/B_AdminModule.php');
	require_once('./bs-module/common/B_CommonModule.php');

	$url = pathinfo($_REQUEST['url']);

	// dispatch rule
	$dir = isset($url['dirname']) ? $url['dirname'] : '.';
	$module = isset($url['filename']) ? $url['filename'] : 'index';
	$page = $module . '.php';
	$path = $dir . '/bs-module/' . $module;
	$file_name = $path . '/' . $page;
	$file_name = str_replace(':', '', $file_name);

	if(file_exists($file_name)) {
		// setup module
		$class = $module;
		if(isset($_REQUEST['method'])) {
			$method = $_REQUEST['method'];
		}
	}
	else {
		$path = './bs-module/index';
		$page = 'index.php';
		$class = 'index';
	}

	$controller = new B_Controller;
	$controller->dispatch($path, $page, $class, $method=null);
