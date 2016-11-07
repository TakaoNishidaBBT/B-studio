<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	$file = pathinfo($_REQUEST['url']);

	require_once('./bs-admin/config/core_config.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Session.php');
	$ses = new B_Session;

	// To get real url
	$url = $_SERVER['REQUEST_URI'];
	$url = preg_replace('"^' . B_CURRENT_ROOT . '"', '', $url);
	$url = preg_replace('/\?.*/', '', $url);
	$url = urldecode($url);
	$_REQUEST['url'] = $url;

	// Not start session. Just read the session valiable for check admin mode or not.
	$session = $ses->read(B_ADMIN_SESSION_NAME);
	if($session['terminal_id'] && $session['user_id']) {
		$admin_mode = true;
		$admin_language = $session['language'];
		$file_info = B_FILE_INFO_W;
		$semaphore = B_FILE_INFO_SEMAPHORE_W;
		$node_view = B_WORKING_RESOURCE_NODE_VIEW;

		define('B_ARTICLE_VIEW',  B_DB_PREFIX . 'v_preview_article');
		define('B_ARTICLE_VIEW2', B_DB_PREFIX . 'v_preview_article2');
		define('B_ARTICLE_VIEW3', B_DB_PREFIX . 'v_preview_article3');
	}
	else {
		$file_info = B_FILE_INFO_C;
		$semaphore = B_FILE_INFO_SEMAPHORE_C;
		$node_view = B_CURRENT_RESOURCE_NODE_VIEW;

		define('B_ARTICLE_VIEW',  B_DB_PREFIX . 'v_article');
		define('B_ARTICLE_VIEW2', B_DB_PREFIX . 'v_article2');
		define('B_ARTICLE_VIEW3', B_DB_PREFIX . 'v_article3');

		if(file_exists(B_LIMIT_FILE_INFO)) {
			if($fp_limit = fopen(B_LIMIT_FILE_INFO, 'r')) {
				$limit = fgets($fp_limit);
				if($limit <= time()) {
					if(file_exists($file_info)) {
						unlink($file_info);
					}
					fclose($fp_limit);
					unlink(B_LIMIT_FILE_INFO);
				}
			}
		}
	}

	// If cache file not exists
	if(!file_exists($file_info) || !filesize($file_info)) {
		createCacheFile($file_info, $semaphore, $node_view);
	}

	if(file_exists($file_info)) {
		$serializedString = file_get_contents($file_info);
		$info = unserialize($serializedString);

		// If requested file exists in resource cache file
		if($info[$url]) {
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

			case 'css':
				header('Content-Type: text/css; charset=' . B_CHARSET);
				break;

			case 'js':
				header('Content-type: text/javascript charset=' . B_CHARSET);
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

			case 'html':
				$contents = file_get_contents(B_RESOURCE_DIR . $info[$url]);
				$encoding = mb_detect_encoding($contents, ${$g_data_set}['encoding']);
				header('Content-Type: text/html; charset=' . $encoding);
				break;

			default:
				header('Content-Type: application/' . strtolower($file['extension']));
				break;
			}
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_info)) . ' GMT');
			readfile(B_RESOURCE_DIR . $info[$url]);
			exit;
		}
	}

	if($admin_mode && file_exists(B_FILE_INFO_THUMB)) {
		$serializedString = file_get_contents(B_FILE_INFO_THUMB);
		$info = unserialize($serializedString);
		if($info[B_CURRENT_ROOT . $url]) {
			switch($file['extension']) {
			case 'avi':
			case 'flv':
			case 'mov':
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'wmv':
				header('Content-Type: image/jpg');
				break;
			default:
				header('Content-Type: image/' . strtolower($file['extension']));
				break;
			}
			readfile(B_UPLOAD_THUMBDIR . $info[B_CURRENT_ROOT . $url]);
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

	function createCacheFile($file_info, $semaphore, $node_view) {
		if(file_exists($semaphore)) return;

		// open and lock semaphore
		$fp_semaphore = fopen($semaphore, 'w');

		require_once('./bs-admin/config/config.php');

		$archive = new B_Log(B_ARCHIVE_LOG_FILE);

		$db = new B_DBaccess($archive);
		$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
		$ret = $db->select_db(B_DB_NME);

		// create serialized resource cache file
		$root = new B_Node($db, B_RESOURCE_NODE_TABLE, $node_view, '', '', 'root', null, 'all', '');
		$root->serialize($data);

		// write serialized data into cache file
		$fp = fopen($file_info, 'w');
		fwrite($fp, serialize($data));
		fclose($fp);

		// close and unlink semaphore
		fclose($fp_semaphore);
		unlink($semaphore);

		return;
	}
