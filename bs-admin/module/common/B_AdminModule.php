<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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

			// bframe_message config
			require_once(B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/config/bframe_message_config.php');
			$this->bframe_message_config = $bframe_message_config;
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

		function refreshCache() {
			$cmdline = 'php ' . B_DOC_ROOT . B_ADMIN_ROOT . 'module/common/cache.php';
			$cmdline .= ' ' . $_SERVER['SERVER_NAME'];
			$cmdline .= ' ' . $_SERVER['DOCUMENT_ROOT'];
			$cmdline .= ' ' . $_SERVER['HTTPS'];

			// kick as a background process
			B_Util::fork($cmdline);
		}

		function replaceCacheFile($file_info, $semaphore, $serialized_string) {
			if(file_exists($semaphore)) return;

			// open semaphore for lock
			if(!$fp_semaphore = fopen($semaphore, 'x')) return;

			// write serialized data into cache file
			$fp = fopen($file_info, 'w');
			fwrite($fp, $serialized_string);
			fclose($fp);
			chmod($file_info, 0777);

			// close and unlink semaphore
			fclose($fp_semaphore);
			unlink($semaphore);
		}

		function getCacheFromDB($version) {
			// get cache from DB
			$version_table = B_DB_PREFIX . B_VERSION_TABLE;
			$current_version = B_DB_PREFIX . B_CURRENT_VERSION_VIEW;
			$sql = "select * from $version_table a, $current_version b
					where a.version_id = b.{$version}";

			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function createThumbnailCacheFile() {
			if(file_exists(B_FILE_INFO_THUMB_SEMAPHORE)) return;

			// open semaphore for lock
			if(!$fp_semaphore = fopen(B_FILE_INFO_THUMB_SEMAPHORE, 'x')) return;

			// create thumb-nails
			$node = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
			$this->total_create_nodes = $node->nodeCount(); 

			if(20 < $this->total_create_nodes) {
				// send progress
				header('Content-Type: application/octet-stream');
				header('Transfer-encoding: chunked');
				flush();
				ob_flush();

				// Send start message
				$response['status'] = 'show';
				$response['progress'] = 0;
				$response['message'] = 'creating thumbnails';
				$progress = 0;
				$this->sendChunk(json_encode($response));

				$this->show_progress = true;

				// set time limit to 5 minutes
				set_time_limit(300);

				// continue whether a client disconnect or not
				ignore_user_abort(true);
			}

			// remove all Thumbnail Cache Files
			$this->removeThumbnailCacheFile();

			if($this->show_progress) $callback = array('obj' => $this, 'method' => '_createThumbnail_callback');
			$index = 0;
			$node->createThumbnail($data, $index, null, $callback);

			// write serialized data into cache file
			$fp = fopen(B_FILE_INFO_THUMB, 'w');
			fwrite($fp, serialize($data));
			fclose($fp);
			chmod(B_FILE_INFO_THUMB, 0777);

			// close and unlock semaphore
			fclose($fp_semaphore);
			unlink(B_FILE_INFO_THUMB_SEMAPHORE);

			if($this->show_progress) {
				$response['status'] = 'finished';
				$response['progress'] = 100;
				$this->sendChunk(',' . json_encode($response));
				$this->sendChunk();	// terminate
				return true;
			}
		}

		function _createThumbnail_callback($file_node) {
			$this->create_nodes++;

			$response['status'] = 'progress';
			$response['progress'] = round($this->create_nodes / $this->total_create_nodes * 100);
			if($this->progress != $response['progress']) {
				$this->sendChunk(',' . json_encode($response));
				$this->progress = $response['progress'];
			}
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

		function sendChunk($response=null) {
			if($response) {
				$response = $response . str_repeat(' ', 8000);
				echo sprintf("%x\r\n", strlen($response));
				echo $response . "\r\n";
			}
			else {
				echo "0\r\n\r\n";
			}
			flush();
			ob_flush();
		}
	}
