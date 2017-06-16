<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
				$this->zip->open('./default/bstudio.zip', ZipArchive::CREATE);
				$this->zip->extractTo(B_RESOURCE_EXTRACT_DIR);
				$this->zip->close();

				// remove all files in B_RESOURCE_DIR, B_UPLOAD_THUMBDIR and B_UPLOAD_DIR
				$this->remove(B_RESOURCE_DIR);
				$this->remove(B_UPLOAD_THUMBDIR);
				$this->remove(B_UPLOAD_DIR);

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

		function remove($dir) {
			$node = new B_FileNode($dir, 'root', null, null, 'all');
			$node->removeChild();
		}

		function copy() {
			// b-admin-files/files
			$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, 'bs-admin-files', null, null, 'all');
			$node->fileCopy(B_DOC_ROOT . B_CURRENT_ROOT, true);

			// files
			$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, 'files', null, null, 'all');
			$node->fileCopy(B_DOC_ROOT . B_CURRENT_ROOT, true);
		}

		function import() {
			if($handle = opendir(B_RESOURCE_EXTRACT_DIR . 'dump')) {
				while(false !== ($file_name = readdir($handle))){
					if($file_name == '.' || $file_name == '..') continue;

					if(preg_match('/bstudio_/', $file_name)) {
						return $this->db->import(B_RESOURCE_EXTRACT_DIR . 'dump/' . $file_name);
					}
				}
				closedir($handle);
			}
		}

		function getErrorMessage() {
			return $this->error_message;
		}
	}
