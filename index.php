<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	$file = pathinfo($_REQUEST['url']);

	require_once('./bs-admin/config/core_config.php');
	require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'class/B_Session.php');
	$ses = new B_Session;

	// to get real url
	$url = $_SERVER['REQUEST_URI'];
	$url = preg_replace('"^' . B_CURRENT_ROOT . '"', '', $url);
	$url = preg_replace('/\?.*/', '', $url);
	$url = urldecode($url);
	$_REQUEST['url'] = $url;

	// not start session. just read the session valiable for check admin mode or not.
	$session = $ses->read(B_ADMIN_SESSION_NAME);
	if($session['terminal_id'] && $session['user_id']) {
		$admin_mode = true;
		$file_info = B_FILE_INFO_W;
		$semaphore = B_FILE_INFO_SEMAPHORE_W;
		$node_view = B_WORKING_RESOURCE_NODE_VIEW;
	}
	else {
		$file_info = B_FILE_INFO_C;
		$semaphore = B_FILE_INFO_SEMAPHORE_C;
		$node_view = B_CURRENT_RESOURCE_NODE_VIEW;

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

	// if cache file not exists
	if(!file_exists($file_info) || !filesize($file_info)) {
		createCacheFile($file_info, $semaphore, $node_view);
	}

	if(file_exists($file_info)) {
		$serializedString = file_get_contents($file_info);
	    $info = unserialize($serializedString);

		// if requested file exists in resource cache file
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

			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				header('Content-Type: image/' . strtolower($file['extension']));
				break;

			default:
				header('Content-Type: application/' . strtolower($file['extension']));
				break;
			}
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", filemtime($file_info)) . " GMT" );
			readfile(B_RESOURCE_DIR . $info[$url]);
			exit;
		}
	}

	if($admin_mode && file_exists(B_FILE_INFO_THUMB)) {
		$serializedString = file_get_contents(B_FILE_INFO_THUMB);
	    $info = unserialize($serializedString);
		if($info[B_CURRENT_ROOT . $url]) {
			header('Content-Type: image/' . strtolower($file['extension']));
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
		// HTTPヘッダー出力
		header("HTTP/1.1 404 Not Found");
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
