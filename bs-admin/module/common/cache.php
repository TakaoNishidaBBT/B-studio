<?php
	require_once('../../config/config.php');

	$archive = new B_Log(B_ARCHIVE_LOG_FILE);
	$log = new B_Log(B_LOG_FILE);
$log->write('cache.php');
exit;
	// Connect to DB
	$db = new B_DBaccess($archive);
	$ret = $db->connect(B_DB_SRV, B_DB_USR, B_DB_PWD, B_DB_CHARSET);
	$ret = $db->select_db(B_DB_NME);

	// create serialized resource cache file
	$node = new B_Node($db, B_RESOURCE_NODE_TABLE, B_WORKING_RESOURCE_NODE_VIEW, '', '', 'root', null, 'all', '');
	$node->serialize($data);
	$serialized_string = serialize($data);

	$sql = "select * from " . B_DB_PREFIX . "v_current_version";
	$rs = $this->db->query($sql);
	$row = $this->db->fetch_assoc($rs);

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
	$table = new B_Table($this->db, 'version');
	$param['version_id'] = $row['working_version_id'];
	$param['cache'] = $serialized_string;
	$ret = $table->update($param);

