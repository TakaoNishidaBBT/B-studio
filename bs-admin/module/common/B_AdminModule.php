<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class B_AdminModule extends B_Module {
		function __construct($file_path) {
			parent::__construct($file_path);

			$auth = new B_AdminAuth;
			$auth->getUserInfo($this->user_id, $this->user_name, $this->user_auth);

			// HTML header
			require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/config/html_header_config.php');
			$this->createHtmlHeader($html_header_config);

			// Version info
			$this->getVersionInfo();

			require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/config/pager_config.php');
			$this->pager_config = $pager_config;
		}

		function getVersionInfo() {
			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$this->version = $this->db->fetch_assoc($rs);

			// Set version info
			$this->version_info = __('Published version:%PUBLISH_VERSION% &nbsp;Working version:%WROKING_VERSION%');
			$this->version_info = str_replace('%PUBLISH_VERSION%', $this->version['current_version'], $this->version_info);
			$this->version_info = str_replace('%WROKING_VERSION%', $this->version['working_version'], $this->version_info);
		}

		function removeCacheFile() {
			if(file_exists(B_FILE_INFO_W)) {
				unlink(B_FILE_INFO_W);
			}
			// if current and working versions are same
			if($this->version['current_version'] == $this->version['working_version'] && file_exists(B_FILE_INFO_C)) {
				unlink(B_FILE_INFO_C);
			}
		}

		function createCacheFile($file_info, $semaphore, $node_view) {
			if(file_exists($semaphore)) return;

			// open semaphore for lock
			$fp_semaphore = fopen($semaphore, 'w');

			// create serialized resource cache file
			$node = new B_Node($this->db, B_RESOURCE_NODE_TABLE, $node_view, '', '', 'root', null, 'all', '');
			$node->serialize($data);

			// write serialized data into cache file
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

			// continue whether a client disconnect or not
			ignore_user_abort(true);

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
			if($limit) {
				$fp = fopen($file_info, 'w');
				fwrite($fp, $limit);
				fclose($fp);
			}
			else {
				if(file_exists($file_info)) {
					unlink($file_info);
				}
			}
		}

		function getImgHTML($img_path, $max_width, $max_height) {
			if(!$img_path) return;
			if(!file_exists($img_path)) return;

			$image_size = getimagesize($img_path);

			if($image_size[0] > $max_width) {
				if(($image_size[0] / $max_width) > ($image_size[1] / $max_height)) {
					$width = $max_width;
					$height = $image_size[1] * $width / $image_size[0];
				}
				else {
					$height = $max_height;
					$width = $image_size[0] * $height / $image_size[1];
				}
			}
			else if($image_size[1] > $max_height) {
				$height = $max_height;
				$width = $image_size[0] * $height / $image_size[1];
			}
			else {
				$width = $image_size[0];
				$height = $image_size[1];
			}

			$html = '<img src="%IMG_URL%" width="%WIDTH%" height="%HEIGHT%" alt="" />';
			$html = str_replace('%IMG_URL%', B_UPLOAD_URL . $img_path, $html);
			$html = str_replace('%WIDTH%', $width, $html);
			$html = str_replace('%HEIGHT%', $height, $html);

			return $html;
		}
	}
