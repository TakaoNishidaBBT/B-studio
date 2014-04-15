<?php
	require_once('./bs-admin/config/config.php');
	require_once('./bs-admin/module/common/B_AdminModule.php');
	require_once('./bs-module/common/B_CommonModule.php');

	$util = new B_Util();
	$url = $util->pathinfo($_REQUEST['url']);

	// dispatch rule
	$dir = $url['dirname'] ? $url['dirname'] : '.';
	$module = $url['filename'] ? $url['filename'] : 'index';
	$page = $module . '.php';
	$path = $dir . "/bs-module/" . $module;
	$file_name = $path . '/' . $page;

	if(file_exists($file_name)) {
		mb_language('Japanese');

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
	$controller->dispatch($path, $page, $class, $method);
