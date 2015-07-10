<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class contents_install extends B_Module {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function install() {
			if(!class_exists('ZipArchive')) {
				return true;
			}
			try {
				$this->zip = new ZipArchive();
				$this->zip->open('./default/bstudio.zip');
				$this->zip->extractTo(B_RESOURCE_EXTRACT_DIR);
				$this->zip->close();

				$this->copy();
				$this->import();

				// remove extract files
				$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, '/', null, null, 'all');
				$node->remove();

				// remove cache files
				if(file_exists(B_FILE_INFO_W)) {
					unlink(B_FILE_INFO_W);
				}
				if(file_exists(B_FILE_INFO_C)) {
					unlink(B_FILE_INFO_C);
				}
				if(file_exists(B_FILE_INFO_THUMB)) {
					unlink(B_FILE_INFO_THUMB);
				}
			}
			catch (Exception $e) {
				$this->error_message = '<p class="error-message">' . $e->getMessage() . '</p>';
				$this->error_message.= '<p class="error-message">' . $this->db->getErrorMsg() . '</p>';
				return false;
			}

			return true;
		}

		function copy() {
			// b-admin-files/files
			$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR . 'bs-admin-files/', 'files', null, null, 'all');
			$node->_copy(B_ADMIN_FILES_DIR, true);

			// b-admin-files/thumbs
			$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR . 'bs-admin-files/', 'thumbs', null, null, 'all');
			$node->_copy(B_ADMIN_FILES_DIR);

			// files
			$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, 'files', null, null, 'all');
			$node->_copy(B_DOC_ROOT . B_CURRENT_ROOT, true);
		}

		function import() {
			$handle = opendir(B_RESOURCE_EXTRACT_DIR . 'dump');
			while(false !== ($file_name = readdir($handle))){
				if($file_name == '.' || $file_name == '..') continue;

				if(preg_match('/bstudio_/', $file_name)) {
					return $this->db->import(B_RESOURCE_EXTRACT_DIR . 'dump/' . $file_name);
				}
			}
		}

		function getErrorMessage() {
			return $this->error_message;
		}
	}
