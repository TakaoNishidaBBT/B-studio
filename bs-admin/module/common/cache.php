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
	$_SERVER['HTTPS'] = $argv[3];

	require_once('../../global/b_global_function.php');
	require_once('../../config/config.php');

	$archive = new B_Log(B_ARCHIVE_LOG_FILE);
	$log = new B_Log(B_LOG_FILE);

	// Connect to DB
	$db = new B_DBaccess($archive);
	$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET, B_DB_NME);

	// start transaction
	$db->begin();

	$sql = "select * from " . B_DB_PREFIX . "v_current_version";
	$rs = $db->query($sql);
	$row = $db->fetch_assoc($rs);

	// create serialized resource cache data
	$node = new B_Node($db, B_RESOURCE_NODE_TABLE, B_WORKING_RESOURCE_NODE_VIEW, null, null, 'root', null, 'all', null);
	$node->serialize('w', $data_w);
	$serialized_string_w = serialize($data_w);

	$node->serialize('c', $data_c);
	$serialized_string_c = serialize($data_c);

	// register cache data to version table
	$table = new B_Table($db, 'version');
	$param['version_id'] = $row['working_version_id'];
	$param['cache_w'] = $serialized_string_w;
	$param['cache_c'] = $serialized_string_c;
	$ret = $table->update($param);

	// end of transaction
	if($ret) {
		$db->commit();
	}
	else {
		$db->rollback();
	}

	$sql = "select * from " . B_DB_PREFIX . "v_current_version";
	$rs = $db->query($sql);
	$row = $db->fetch_assoc($rs);

	// overwrite serialized data to working version cache file
	$fp = fopen(B_FILE_INFO_W, 'w');
	fwrite($fp, $serialized_string_w);
	fclose($fp);

	// if current_version is the same as working_version
	if($row['current_version_id'] == $row['working_version_id']) {
		// overwrite serialized data to current version cache file
		$fp = fopen(B_FILE_INFO_C, 'w');
		fwrite($fp, $serialized_string_c);
		fclose($fp);
	}
