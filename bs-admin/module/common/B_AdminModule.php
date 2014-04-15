<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class B_AdminModule extends B_Module {
		function __construct($file_path) {
			parent::__construct($file_path);

			$auth = new B_AdminAuth;
			$auth->getUserInfo($this->user_id, $this->user_name, $this->user_auth);

			// HTMLヘッダー
			require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/config/html_header_config.php');
			$this->createHtmlHeader($html_header_config);

			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$this->version = $this->db->fetch_assoc($rs);

			// バージョン情報
			$this->version_info = '　公開バージョン：' . $this->version['current_version'] . '　';
			$this->version_info.= '　作業中バージョン：' . $this->version['working_version'];

			require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/config/pager_config.php');
			$this->pager_config = $pager_config;
		}

		function removeCacheFile() {
			if(file_exists(B_FILE_INFO_W)) {
				unlink(B_FILE_INFO_W);
			}
			// 同一バージョン
			if($this->version['current_version'] == $this->version['working_version'] && file_exists(B_FILE_INFO_C)) {
				unlink(B_FILE_INFO_C);
			}
		}

		function createCacheFile($file_info, $semaphore, $node_view) {
			if(file_exists($semaphore)) return;

			// open semaphore for lock
			$fp_semaphore = fopen($semaphore, 'w');

			$node = new B_Node($this->db, B_RESOURCE_NODE_TABLE, $node_view, '', '', 'root', null, 'all', '');
			$node->serialize($data);

			$fp = fopen($file_info, 'w');
	        fwrite($fp, serialize($data));
			fclose($fp);

			// close and unlink semaphore
			fclose($fp_semaphore);
			unlink($semaphore);
		}

		function createThumbnailCacheFile() {
			if(file_exists(B_FILE_INFO_THUMB_SEMAPHORE)) return;

			// open semaphore for lock
			$fp_semaphore = fopen(B_FILE_INFO_THUMB_SEMAPHORE, 'w');

			// set time limit to 2 minutes
			set_time_limit(120);

			// remove all Thumbnail Cache Files
			$this->removeThumbnailCacheFile();

			// create thumb-nails
			$node = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
			$node->createthumbnail($data);

			// write serialized data into cache file
			$fp = fopen(B_FILE_INFO_THUMB, 'w');
			fwrite($fp, serialize($data));
			fclose($fp);

			// close and unlock semaphore
			fclose($fp_semaphore);
			unlink(B_FILE_INFO_THUMB_SEMAPHORE);
		}

		function removeThumbnailCacheFile() {
			$handle = opendir(B_UPLOAD_THUMBDIR);
			while(false !== ($file_name = readdir($handle))){
				if($file_name == '.' || $file_name == '..') continue;
				unlink(B_UPLOAD_THUMBDIR . $file_name);
			}
			closedir($handle);
		}

		function createLimitFile($file_info, $limit) {
			if(!$limit) return;

			$fp = fopen($file_info, 'w');
	        fwrite($fp, $limit);
			fclose($fp);
		}
	}
