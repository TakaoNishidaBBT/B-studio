<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class B_AdminModule extends B_Module {
		public $version;
		public $version_info;
		public $auth;
		public $user_id;
		public $user_name;
		public $user_auth;
		public $language;
		public $exhibitor;
		public $node_id;
		public $site_settings;
		public $lang;
		public $site_settings_table;
		public $html_mail_header;
		public $list_header_config;
		public $list_config;
		public $entry_status_info_config;
		public $entry_form_config;
		public $enquete_config;
		public $preview_config;
		public $chat_list_config;
		public $booth_chat_list_config;
		public $session_list_config;
		public $session_text_list_config;
		public $session_text_list_config2;
		public $session_text_list_config_ex;
		public $session_text_list_config2_ex;
		public $top_page_table;
		public $top_page_contents;
		public $global_navi;
		public $event_type;
		public $ses_exhibition;
		public $pager_config;
		public $bframe_message_config;
		public $default_row_per_page;
		public $sql_where;
		public $select_message;
		public $site_title;
		public $title;
		public $agent;
		public $bframe_message;
		public $view_file;
		public $session_list;
		public $event_hybrid_online;
		public $session_data_list;
		public $status;

		function __construct($file_path) {
			parent::__construct($file_path);

			$auth = new B_AdminAuth;
			$auth->getUserInfo($this->user_id, $this->user_name, $this->user_auth, $this->language);

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
