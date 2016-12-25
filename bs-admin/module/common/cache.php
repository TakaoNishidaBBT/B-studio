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
	$_SERVER['HTTPS'] = $argv[3];

	require_once('../../config/config.php');
	require_once('../../language/language.php');

	$archive = new B_Log(B_ARCHIVE_LOG_FILE);
	$log = new B_Log(B_LOG_FILE);

	// Connect to DB
	$db = new B_DBaccess($archive);
	$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
	$ret = $db->select_db(B_DB_NME);

	// create serialized resource cache file
	$node = new B_Node($db, B_RESOURCE_NODE_TABLE, B_WORKING_RESOURCE_NODE_VIEW, '', '', 'root', null, 'all', '');
	$node->serialize($data);
	$serialized_string = serialize($data);

	$sql = "select * from " . B_DB_PREFIX . "v_current_version";
	$rs = $db->query($sql);
	$row = $db->fetch_assoc($rs);

	// write serialized data into working version cache file
	$fp = fopen(B_FILE_INFO_W, 'w');
	fwrite($fp, $serialized_string);
	fclose($fp);

	if($row['current_version_id'] == $row['working_version_id']) {
		// write serialized data into current version cache file
		$fp = fopen(B_FILE_INFO_W, 'w');
		fwrite($fp, $serialized_string);
		fclose($fp);
	}

	// egister cache into version table
	$table = new B_Table($db, 'version');
	$param['version_id'] = $row['working_version_id'];
	$param['cache'] = $serialized_string;
	$ret = $table->update($param);
