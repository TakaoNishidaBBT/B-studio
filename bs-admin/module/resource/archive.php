<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	$_SERVER['SERVER_NAME'] = $argv[1];
	$_SERVER['DOCUMENT_ROOT'] = $argv[2];
	$working_version_id = $argv[3];
	$revision_id = $argv[4];
	$file_path = $argv[5];

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

	for($i=6; $i < $argc; $i++) {
		$node_id = $argv[$i];
		$node = new B_Node($db
					, B_RESOURCE_NODE_TABLE
					, B_WORKING_RESOURCE_NODE_VIEW
					, $working_version_id
					, $revision_id
					, $node_id
					, null
					, 'all'
					, null);

		$node->serializeForDownload($data);
		foreach($data as $key => $value) {
			if($value) {
				$file = B_RESOURCE_DIR . $value;
				$zip->addFile($file, $key);
			}
			else {
				$zip->addEmptyDir($key);
			}
		}
	}

	$zip->close();
