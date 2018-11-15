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
	$dir = $argv[3];
	$file_path = $argv[4];

	require_once('../../global/b_global_function.php');
	require_once('../../config/config.php');

	// create archive file
	$zip = new ZipArchive();
	if(!$zip->open($file_path, ZipArchive::CREATE)) {
		exit;
	}

	for($i=5; $i < $argc; $i++) {
		$node_id = $argv[$i];
		$node = new B_FileNode($dir, $node_id, null, null, 'all');
		$node->serializeForDownload($data);
		foreach($data as $key => $value) {
			if($value) {
				$zip->addFile($value, $key);
			}
			else {
				$zip->addEmptyDir($key);
			}
		}
	}
	$zip->close();
