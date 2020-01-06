<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	require_once('bs-admin/global/b_global_function.php');
	require_once('bs-admin/config/config.php');

	$file = B_Util::pathinfo($_REQUEST['url']);

	// To get real url (remove current root and parameter)
	$url = $_SERVER['REQUEST_URI'];
	$url = preg_replace('"^' . B_CURRENT_ROOT . '"', '', $url);
	$url = preg_replace('/\?.*/', '', $url);
	$url = urldecode($url);
	$_REQUEST['url'] = $url;

	// Not start session. Just read the session variable for check admin mode or not.
	$ses = new B_Session;
	$session = $ses->read(B_ADMIN_SESSION_NAME);

	if($session['terminal_id'] && $session['user_id']) {
		$admin_mode = true;
		$admin_language = $session['language'];
		$version = 'w';
		$file_info = B_FILE_INFO_W;
		$semaphore = B_FILE_INFO_SEMAPHORE_W;
		$node_view = B_WORKING_RESOURCE_NODE_VIEW;

		define('B_ARTICLE_VIEW',  B_DB_PREFIX . 'v_preview_article');
		define('B_ARTICLE_VIEW2', B_DB_PREFIX . 'v_preview_article2');
		define('B_ARTICLE_VIEW3', B_DB_PREFIX . 'v_preview_article3');
	}
	else {
		$version = 'c';
		$file_info = B_FILE_INFO_C;
		$semaphore = B_FILE_INFO_SEMAPHORE_C;
		$node_view = B_CURRENT_RESOURCE_NODE_VIEW;

		define('B_ARTICLE_VIEW',  B_DB_PREFIX . 'v_article');
		define('B_ARTICLE_VIEW2', B_DB_PREFIX . 'v_article2');
		define('B_ARTICLE_VIEW3', B_DB_PREFIX . 'v_article3');
	}

	// reserved version exists
	if(file_exists(B_LIMIT_FILE_INFO)) {
		if($fp_limit = fopen(B_LIMIT_FILE_INFO, 'r')) {
			$limit = fgets($fp_limit);
			if($limit <= time()) {
				fclose($fp_limit);
				unlink(B_LIMIT_FILE_INFO);
				replaceCacheFile();
			}
		}
	}

	// If cache file not exists
	if(!file_exists($file_info) || !filesize($file_info)) {
		createCacheFile($version, $file_info, $semaphore, $remove_file_info, $node_view);
	}

	if(file_exists($file_info)) {
		$serializedString = file_get_contents($file_info);
		$info = unserialize($serializedString);

		// set DirectoryIndex
		if(!$file['basename']) {
			$url.= 'index.html';
			$file = B_Util::pathinfo($url);
		}

		// If requested file exists in resource cache file
		if(array_key_exists($url, $info)) {
			if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
				if(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= filemtime($file_info)) {
					header('HTTP/1.1 304 Not Modified');
					exit;
				}
			}

			switch(strtolower($file['extension'])) {
			case 'swf':
				header('Content-type: application/x-shockwave-flash');
				break;

			case 'js':
				header('Content-type: application/javascript');
				break;

			case 'css':
				header('Content-Type: text/css; charset=' . B_CHARSET);
				break;

			case 'gif':
				header('Content-Type: image/gif');
				break;

			case 'png':
				header('Content-Type: image/png');
				break;

			case 'jpg':
			case 'jpeg':
				header('Content-Type: image/jpeg');
				break;

			case 'svg':
				header('Content-type: image/svg+xml');
				break;

			case 'ico':
				header('Content-type: image/x-icon');
				break;

			case 'html':
				$contents = file_get_contents(B_RESOURCE_DIR . $info[$url]);
				$encoding = mb_detect_encoding($contents, ${$g_data_set}['encoding']);
				header('Content-Type: text/html; charset=' . $encoding);
				eval('?>' . $contents);
				exit;

			default:
				if(!$info[$url]) {
					$path = B_SITE_ROOT . $url . '/';
					header('HTTP/1.1 302 Found');
					header("Location:$path");
					exit;
				}

				header('Content-Type: application/' . strtolower($file['extension']));
				break;
			}
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_info)) . ' GMT');
			readfile(B_RESOURCE_DIR . $info[$url]);
			exit;
		}
	}

	switch(strtolower($file['extension'])) {
	case 'swf':
	case 'jpg':
	case 'jpeg':
	case 'gif':
	case 'png':
		header('HTTP/1.1 404 Not Found');
		exit;
	}

	define('FILE_NAME', __FILE__);
	require_once('./bs-controller/controller.php');

	function replaceCacheFile() {
		$serialized_string = getCacheFromDB('c');

		// write serialized data into cache file
		$fp = fopen(B_FILE_INFO_C, 'w');
		fwrite($fp, $serialized_string);
		fclose($fp);

		return;
	}

	function createCacheFile($version, $file_info, $semaphore, $file_remove_info, $node_view) {
		if(file_exists($semaphore)) return;

		// open and lock semaphore
		if(!$fp_semaphore = fopen($semaphore, 'x')) return;

		$serialized_string = getCacheFromDB($version);

		// write serialized data into cache file
		$fp = fopen($file_info, 'w');
		fwrite($fp, $serialized_string);
		fclose($fp);

		// close and unlink semaphore
		fclose($fp_semaphore);
		if(file_exists($semaphore)) unlink($semaphore);

		return;
	}

	function getCacheFromDB($version) {
		require_once('./bs-admin/config/config.php');

		$archive = new B_Log(B_ARCHIVE_LOG_FILE);

		$db = new B_DBaccess($archive);
		$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
		$ret = $db->select_db(B_DB_NME);

		// get cache from DB
		switch($version) {
		case 'w':
			$version_field = 'working_version_id';
			$cache_field = 'cache_w';
			break;

		case 'c':
			$version_field = 'current_version_id';
			$cache_field = 'cache_c';
			break;
		}

		$version_table = B_DB_PREFIX . B_VERSION_TABLE;
		$current_version = B_DB_PREFIX . B_CURRENT_VERSION_VIEW;
		$sql = "select * from $version_table a, $current_version b
				where a.version_id = b.{$version_field}";

		$rs = $db->query($sql);
		$row = $db->fetch_assoc($rs);

		return $row[$cache_field];
	}
