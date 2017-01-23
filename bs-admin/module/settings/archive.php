<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	$_SERVER['SERVER_NAME'] = $argv[1];
	$_SERVER['DOCUMENT_ROOT'] = $argv[2];
	$file_path = $argv[3];
	$mode = $argv[4];

	require_once('../../config/config.php');
	require_once('../../language/language.php');

	$archive = new B_Log(B_ARCHIVE_LOG_FILE);
	$log = new B_Log(B_LOG_FILE);

	// Connect to DB
	$db = new B_DBaccess($archive);
	$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
	$ret = $db->select_db(B_DB_NME);

	// create archive file
	$zip = new ZipArchive();
	if(!$zip->open($file_path, ZipArchive::CREATE)) {
		exit;
	}

	$node = new B_FileNode(B_ADMIN_FILES_DIR, 'root', null, null, 'all');
	$node->serializeForDownload($admin_file_data);
	if(is_array($admin_file_data)) {
		foreach($admin_file_data as $key => $value) {
			if($value) {
				$info = pathinfo($key);
				if(substr($info['basename'], 0, 1) == '.') continue;
				$zip->addFile($value, B_ADMIN_FILES . $key);
			}
			else {
				$zip->addEmptyDir(B_ADMIN_FILES . $key);
			}
		}
	}

	$node = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
	$node->serializeForDownload($file_data);
	if(is_array($file_data)) {
		foreach($file_data as $key => $value) {
			if($value) {
				$info = pathinfo($key);
				if(substr($info['basename'], 0, 1) == '.') continue;
				$zip->addFile($value, B_UPLOAD_FILES . $key);
			}
			else {
				$zip->addEmptyDir(B_UPLOAD_FILES . $key);
			}
		}
	}

	$dump_file_name = 'bstudio_' . date('YmdHis') . '.sql';
	$dump_file_path = B_DOWNLOAD_DIR . $dump_file_name;
	$db->backupTables($dump_file_path, $mode);

	$zip->addFile($dump_file_path, B_DUMP_FILE . $dump_file_name);
	$zip->close();

	unlink($dump_file_path);
