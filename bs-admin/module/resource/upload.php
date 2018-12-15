<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_upload extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->dir = B_RESOURCE_DIR;
			$this->current_folder = $this->session['current_node'];
		}

		function confirm() {
			$status = true;

			try {
				if(!$this->current_folder) {
					throw new Exception(__('No folder selected'));
				}

	 			// Check file size
				$filesize = $_POST['filesize'];
				$post_max_size = $this->util->decode_human_filesize(ini_get('post_max_size'));
				$upload_max_filesize = $this->util->decode_human_filesize(ini_get('upload_max_filesize'));
				if($filesize > $post_max_size || $filesize > $upload_max_filesize) {
					if($post_max_size < $upload_max_filesize) {
						$limit = ini_get('post_max_size');
					}
					else {
						$limit = ini_get('upload_max_filesize');
					}
					$message = __('The file size is too large. The maximun file upload size is %LIMIT%');
					$message = str_replace('%LIMIT%', $limit, $message);
					throw new Exception($message);
				}
				// Get file info
				$file = B_Util::pathinfo($_POST['filename']);

				// Unify extension to lowercase for windows system
				$file['extension'] = strtolower($file['extension']);

				if(strlen($file['basename']) != mb_strlen($file['basename'])) {
					throw new Exception(__('Multi-byte characters cannot be used'));
				}
				if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file['basename'])) {
					throw new Exception(__('The following characters cannot be used in file or folder names (\ / : * ? " < > | space)'));
				}

				if($file['extension'] == 'zip') {
					switch($this->request['extract_mode']) {
					case 'confirm':
						$response_mode = 'zipConfirm';
						$message = __('Extract %FILE_NAME% ?');
						$message = str_replace('%FILE_NAME%', $file['basename'], $message);
						break;

					case 'noextract':
						if($this->file_exists($file['basename']) && $this->request['mode'] == 'confirm') {
							$response_mode = 'confirm';
							$message = __('%FILE_NAME% already exists.<br />Are you sure you want to overwrite?');
							$message = str_replace('%FILE_NAME%', $file['basename'], $message);
						}
						break;
					}
				}
				else {
					if($this->request['mode'] == 'confirm' && $this->file_exists($file['basename'])) {
						$response_mode = 'confirm';
						$message = __('%FILE_NAME% already exists.<br />Are you sure you want to overwrite?');
						$message = str_replace('%FILE_NAME%', $file['basename'], $message);
					}
				}
			}
			catch(Exception $e) {
				$status = false;
				$message = $e->getMessage();
			}

			$response['status'] = $status;
			$response['mode'] = $response_mode;
			$response['message'] = $message;

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
			exit;
		}

		function file_exists($file_name){
			$parent_node = $this->current_folder;
			$this->resource_node_table = new B_Table($this->db, B_RESOURCE_NODE_TABLE);

			$sql = "select * from %NODE_VIEW%
					where parent_node = '%PARENT_NODE%'
					and node_name='%FILE_NAME%'
					and del_flag = '0'";
			$sql = str_replace('%NODE_VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql);
			$sql = str_replace('%PARENT_NODE%', $this->current_folder, $sql);
			$sql = str_replace('%FILE_NAME%', $file_name, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			if($row) {
				return true;
			}
			else {
				return false;
			}
		}

		function upload() {
			$status = true;

			try {
				$node = new B_Node($this->db
								 , B_RESOURCE_NODE_TABLE
								 , B_WORKING_RESOURCE_NODE_VIEW
								 , $this->version['working_version_id']
								 , $this->version['revision_id']
								 , null
								 , null
								 , 0
								 , null);
				$current_node_status = $node->getStatus($this->current_folder);

				// Get file info
				$file = B_Util::pathinfo($_FILES['Filedata']['name']);

				if(!is_dir($this->dir)) {
					if(mkdir($this->dir)) {
						chmod($this->dir, 0777);
					}
					else {
						throw new Exception(__('Failed to create directory'));
					}
				}

				if(strtolower($file['extension']) == 'zip' && class_exists('ZipArchive') && $this->request['extract_mode'] == 'extract') {
					// Set time limit to 10 minutes
					set_time_limit(600);

					// Continue whether a client disconnect or not
					ignore_user_abort(true);

					$zip_file = B_RESOURCE_WORK_DIR . $file['basename'];
					if($status = $this->_move_uploaded_file($_FILES['Filedata']['tmp_name'], $zip_file)) {
						// Check zip file inside
						$this->checkZipFile($zip_file);

						usleep(300000);

						// send progress
						header('Content-Type: application/octet-stream');
						header('Transfer-encoding: chunked');
						flush();
						ob_flush();

						// Send start message
						$response['status'] = 'extracting';
						$response['progress'] = 0;
						$this->sendChunk(json_encode($response));

						// Extract
						$zip = new ZipArchive();
						$zip->open($zip_file);
						$zip->extractTo(B_RESOURCE_EXTRACT_DIR);
						$zip->close();
						unlink($zip_file);

						// Controll extracted files
						$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, '/', null, null, 'all');

						// except file or folder
						$this->except = array_flip(array('__MACOSX', '._' . $file['file_name']));

						// Count extract files
						$this->extracted_files = $node->nodeCount($this->except);

						$this->registerd_files = 0;

						// Register extract files
						$node->walk($this, register_archive);
						$node->remove();

						// kick refresh-cache process
						$this->refreshCache();

						// Create response node_info
						foreach($this->registered_archive_node as $value) {
							$node = new B_Node($this->db
												, B_RESOURCE_NODE_TABLE
												, B_WORKING_RESOURCE_NODE_VIEW
												, $this->version['working_version_id']
												, $this->version['revision_id']
												, $value
												, null
												, 0
												, null
												, false
												, 'auto');
							$path = $node->getParentPath();
							$response['node_info'][] = $node->getNodeList('', '', B_RESOURCE_DIR, $path, $current_node_status);
						}
					}

					$response['status'] = $status;
					$response['progress'] = 100;
					$this->sendChunk(',' . json_encode($response));
					$this->sendChunk();	// terminate

					exit;
				}
				else {
					$node_id = $this->register($file);
					$node = new B_Node($this->db
										, B_RESOURCE_NODE_TABLE
										, B_WORKING_RESOURCE_NODE_VIEW
										, $this->version['working_version_id']
										, $this->version['revision_id']
										, $node_id
										, null
										, 1
										, null
										, false
										, 'auto');
					$path = $node->getParentPath();
					$response['node_info'][] = $node->getNodeList('', '', B_RESOURCE_DIR, $path, $current_node_status);

					// last upload file
					if($this->post['last_file'] == 'true') {
						// kick refresh-cache process
						$this->refreshCache();
					}

				}
			}
			catch(Exception $e) {
				$status = false;
				$message = $e->getMessage();
			}

			$response['status'] = $status;
			$response['message'] = $message;
			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);

			exit;
		}

		function cancel() {
			// kick refresh-cache process
			$this->refreshCache();

			$response['status'] = true;
			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
		}

		function checkZipFile($zip_file) {
			$zip = new ZipArchive();
			if($zip->open($zip_file, ZipArchive::CHECKCONS) !== true) {
				throw new Exception(__('Faild tp open zip file.'));
			}

			for($i=0; $i < $zip->numFiles; $i++) {
				$stat = $zip->statIndex($i);
				$file_name = mb_convert_encoding($stat['name'], 'UTF-8', B_MB_DETECT_ORDER);
				if(strlen($file_name) != mb_strlen($file_name)) {
					$zip->close();
					unlink($zip_file);
					throw new Exception(__('Multi-byte characters cannot be used in file names. Please check contents of the zip file.'));
				}
			}

			$zip->close();
		}

		function register_archive($node) {
			if(!$node->parent) return true;

			// except file or folder (stop walking)
			if(array_key_exists($node->file_name, $this->except)) return false;

			if(!$this->_register_archive($node, $node_id, $contents_id)) {
				$response['status'] = $false;
				$response['message'] = 'DB error';
				$this->sendChunk(',' . json_encode($response));
				$this->sendChunk();	// terminate

				exit;
			}

			$node->db_node_id = $node_id;

			if($node->node_type != 'folder') {
				$file = B_Util::pathinfo($node->path);
				if(rename(B_RESOURCE_EXTRACT_DIR . $node->path, $this->dir . $contents_id . '.' . $file['extension'])) {
					chmod($this->dir . $contents_id . '.' . $file['extension'], 0777);
					$this->createthumbnail($this->dir, $contents_id, $file['extension'], B_THUMB_PREFIX);
				}
			}

			if(!$node->parent->db_node_id) {
				$this->registered_archive_node[] = $node_id;
			}

			$this->registerd_files++;
			$response['status'] = 'extracting';
			$response['progress'] = round($this->registerd_files / $this->extracted_files * 100);
			if($this->progress != $response['progress']) {
				$this->sendChunk(',' . json_encode($response));
				$this->progress = $response['progress'];
			}

			return true;
		}

		function _register_archive($node, &$node_id, &$contents_id) {
			$this->resource_node_table = new B_Table($this->db, B_RESOURCE_NODE_TABLE);

			if($node->parent->db_node_id) {
				$parent_node = $node->parent->db_node_id;
			}
			else {
				$parent_node = $this->current_folder;
			}

			$sql = "select * from " . B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW . "
					where parent_node = '%PARENT_NODE%'
					and node_name='%FILE_NAME%'
					and del_flag = '0'";
			$sql = str_replace('%PARENT_NODE%', $parent_node, $sql);
			$sql = str_replace('%FILE_NAME%', $node->file_name, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if($row) {
				if($row['version_id'] == $this->version['working_version_id'] && $row['revision_id'] == $this->version['revision_id']) {
					return $this->update($node->fullpath, $row, $node->file_name, $node_id, $contents_id);
				}
				else {
					return $this->updateNode($node->fullpath, $row, $node->file_name, $node_id, $contents_id);
				}
			}
			else {
				return $this->insert($node->fullpath, $parent_node, $node->file_name, $node->node_type, $node->node_class, $node_id, $contents_id);
			}
		}

		function register($file) {
			if(!$this->_register($file['basename'], $node_id, $contents_id)) {
				throw new Exception(__('DB error'));
			}

			if($this->_move_uploaded_file($_FILES['Filedata']['tmp_name'], $this->dir . $contents_id . '.' . $file['extension'])) {
				chmod($this->dir . $contents_id . '.' . $file['extension'], 0777);
				$this->createthumbnail($this->dir, $contents_id, $file['extension'], B_THUMB_PREFIX);
			}
			return $node_id;
		}

		function _register($file_name, &$node_id, &$contents_id) {
			$parent_node = $this->current_folder;
			$this->resource_node_table = new B_Table($this->db, B_RESOURCE_NODE_TABLE);

			$sql = "select * from %NODE_VIEW%
					where parent_node = '%PARENT_NODE%'
					and node_name='%FILE_NAME%'
					and del_flag = '0'";
			$sql = str_replace('%NODE_VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql);
			$sql = str_replace('%PARENT_NODE%', $this->current_folder, $sql);
			$sql = str_replace('%FILE_NAME%', $file_name, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if($row) {
				if($row['version_id'] == $this->version['working_version_id'] && $row['revision_id'] == $this->version['revision_id']) {
					return $this->update($_FILES['Filedata']['tmp_name'], $row, $file_name, $node_id, $contents_id);
				}
				else {
					return $this->updateNode($_FILES['Filedata']['tmp_name'], $row, $file_name, $node_id, $contents_id);
				}
			}
			else {
				return $this->insert($_FILES['Filedata']['tmp_name'], $parent_node, $file_name, 'file', 'leaf', $node_id, $contents_id);
			}
		}

		function update($filepath, $row, $file_name, &$node_id, &$contents_id) {
			// start transaction
			$this->db->begin();

			$node_id = $row['node_id'];
			$contents_id = $row['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
			$param['node_id'] = $row['node_id'];
			$param['node_name'] = $file_name;
			$param['contents_id'] = $contents_id;
			$param['version_id'] = $this->version['working_version_id'];
			$param['revision_id'] = $this->version['revision_id'];
			$param['update_user'] = $this->user_id;
			$param['update_datetime'] = time();
			$param['create_datetime'] = time();

			// set file size
			if($row['node_type'] == 'file') {
				$param['file_size'] = filesize($filepath);
				$param['human_file_size'] = B_Util::human_filesize($param['file_size'], 'K');
				$size = B_Util::getimagesize($filepath, $file_name);
				if($size) {
					$param['image_size'] = $size[0] * $size[1];
					$param['human_image_size'] = $size[0] . 'x' . $size[1];
				}
				else {
					$param['image_size'] = '';
					$param['human_image_size'] = '';
				}
			}

			$ret = $this->resource_node_table->update($param);

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
			}

			return $ret;
		}

		function updateNode($filepath, $row, $file_name, &$node_id, &$contents_id) {
			$node_id = $row['node_id'];

			$node = new B_Node($this->db
							, B_RESOURCE_NODE_TABLE
							, B_WORKING_RESOURCE_NODE_VIEW
							, $this->version['working_version_id']
							, $this->version['revision_id']
							, $row['node_id']
							, null
							, 1
							, null);

			// start transaction
			$this->db->begin();
			$node->cloneNode($row['node_id']);
			$contents_id = $row['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
			$ret = $node->setContentsId($contents_id, $this->user_id);

			// set file size
			if($row['node_type'] == 'file') {
				$param['file_size'] = filesize($filepath);
				$param['human_file_size'] = B_Util::human_filesize($param['file_size'], 'K');
			}
			$size = B_Util::getimagesize($filepath, $file_name);
			if($size) {
				$param['image_size'] = $size[0] * $size[1];
				$param['human_image_size'] = $size[0] . 'x' . $size[1];
			}
			$param['node_name'] = $file_name;

			$ret &= $node->updateNode($param, $this->user_id);

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
			}

			return $ret;
		}

		function insert($filepath, $parent_node, $file_name, $node_type, $node_class, &$node_id, &$contents_id) {
			$node = new B_Node($this->db
							, B_RESOURCE_NODE_TABLE
							, B_WORKING_RESOURCE_NODE_VIEW
							, $this->version['working_version_id']
							, $this->version['revision_id']
							, $parent_node
							, null
							, 1
							, null);

			// start transaction
			$this->db->begin();
			$ret = $node->insert($node_type, $node_class, $this->user_id, $new_node_id, $new_node_name);
			if($ret) {
				$node_id = $new_node_id;
				$new_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $new_node_id
									, null
									, 1
									, null);

				$contents_id = $new_node_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
				$ret = $new_node->setContentsId($contents_id, $this->user_id);

				$param['node_name'] = $file_name;

				// set file size
				if($node_type == 'file') {
					$param['file_size'] = filesize($filepath);
					$param['human_file_size'] = B_Util::human_filesize($param['file_size'], 'K');
					$size = B_Util::getimagesize($filepath, $file_name);
					if($size) {
						$param['image_size'] = $size[0] * $size[1];
						$param['human_image_size'] = $size[0] . 'x' . $size[1];
					}
				}

				$ret &= $new_node->updateNode($param, $this->user_id);
				$this->db->commit();
			}
			else {
				$this->db->rollback();
			}
			return $ret;
		}

		function _move_uploaded_file($source, $destination) {
			if(!move_uploaded_file($source, $destination)) {
				switch($_FILES['Filedata']['error']) {
				case 1:
					$this->error_message = __('The uploaded file exceeds the upload_max_filesize directive in php.ini.');
					break;

				case 2:
					$this->error_message = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
					break;

				case 3:
					$this->error_message = __('The uploaded file was only partially uploaded.');
					break;

				case 4:
					$this->error_message = __('No file was uploaded.');
					break;

				case 6:
					$this->error_message = __('Missing a temporary folder. Introduced in PHP 5.0.3.');
					break;

				case 7:
					$this->error_message = __('Failed to write file to disk. Introduced in PHP 5.1.0.');
					break;

				case 8:
					$this->error_message = __('A PHP extension stopped the file upload.');
					break;

				default:
					$this->error_message = 'move_uploaded_file error';
					break;
				}
				$this->log->write('ERROR:' . $this->error_message);
				throw new Exception($this->error_message);

				return false;
			}

			return true;
		}

		function createthumbnail($dir, $file_name, $file_extension, $prefix) {
			$source_file_path = $dir . $file_name . '.' . $file_extension;
			$thumbnail_file_path = $dir . $prefix . $file_name . '.' . $file_extension;

			if(B_Util::createthumbnail($source_file_path, $thumbnail_file_path, B_THUMB_MAX_SIZE)) {
				chmod($thumbnail_file_path, 0777);
			}
		}
	}
