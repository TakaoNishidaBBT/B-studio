<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_upload extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->current_folder = $this->global_session['resource']['current_node'];
		}

		function upload() {
			$status = true;

			if(!$this->current_folder) {
				$status = false;
				$this->error_message = 'フォルダが選択されていません';
			}

			if($status) {
				$this->dir = B_RESOURCE_DIR;

				// アップロードファイル名取得
				$file = $this->util->pathinfo($_FILES['Filedata']['name']);

				// 拡張子は小文字に統一（windowsにダウンロードした時のため）
				$file['extension'] = strtolower($file['extension']);

				if(strlen($file['basename']) != mb_strlen($file['basename'])) {
					$this->error_message = '日本語ファイル名は使用できません。';
					$status = false;
				}
				if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file['basename'])) {
					$this->error_message = 'ファイル名／フォルダ名に次の文字は使えません \ / : * ? " < > | スペース';;
					$status = false;
				}
			}

			if($status) {
				if(!is_dir($this->dir)) {
					if(mkdir($this->dir)) {
						chmod($this->dir, 0777);
					}
					else {
						$status = false;
						$this->error_message = "ディレクトリの作成に失敗しました";
					}
				}
			}
			if($status) {
				if($_REQUEST['mode'] == 'confirm'){
					$ret = $this->file_exists($file['basename']);
					if($ret) {
						$this->error_message = $file['basename'] . 'は既に存在します。<br />上書きしてもよろしいですか？';
						$mode = 'confirm';
						$status = false;
					}
				}
			}
			if($status) {
				if(strtolower($file['extension']) == 'zip' && class_exists('ZipArchive')) {

					// ブラウザを閉じても処理を継続
					ignore_user_abort(true);

					// set time limit to 10 minutes
					set_time_limit(600);

					$zip_file = B_RESOURCE_WORK_DIR . $file['basename'];
					if($status = $this->_move_uploaded_file($_FILES['Filedata']['tmp_name'], $zip_file)) {
						$zip = new ZipArchive();
						$zip->open($zip_file);
						$zip->extractTo(B_RESOURCE_EXTRACT_DIR);
						$zip->close();
						unlink($zip_file);

						$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, '/', null, null, 'all');
						$node->walk($this, regist_archive);

						$node->remove();

						$this->removeCacheFile();
					}
				}
				else {
					$this->regist($file);
				}
			}

			$response['status'] = $status;
			$response['mode'] = $mode;
			$response['message'] = $this->error_message;

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
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

		function regist_archive($node) {
			if(!$node->parent) return;

			$ret = $this->_regist_archive($node, $node_id, $contents_id);
			if($ret) {
				$node->db_node_id = $node_id;
			}
			if($node->node_type != 'folder') {
				$file = pathinfo($node->path);
				if(rename(B_RESOURCE_EXTRACT_DIR . $node->path, $this->dir . $contents_id . '.' . $file['extension'])) {
					chmod($this->dir . $contents_id . '.' . $file['extension'], 0777);
					$this->createthumbnail($this->dir, $contents_id, $file['extension'], B_THUMB_PREFIX, B_THUMB_MAX_SIZE);
				}
			}
		}

		function _regist_archive($node, &$node_id, &$contents_id) {
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
					return $this->update($row, $node_id, $contents_id);
				}
				else {
					return $this->updateNode($row, $node_id, $contents_id);
				}
			}
			else {
				return $this->insert($parent_node, $node->file_name, $node->node_type, $node->node_class, $node_id, $contents_id);
			}
		}

		function regist($file) {
			if(!$this->_regist($file['basename'], $contents_id)) exit;

			if($this->_move_uploaded_file($_FILES['Filedata']['tmp_name'], $this->dir . $contents_id . '.' . $file['extension'])) {
				chmod($this->dir . $contents_id . '.' . $file['extension'], 0777);
				$this->createthumbnail($this->dir, $contents_id, $file['extension'], B_THUMB_PREFIX, B_THUMB_MAX_SIZE);
				$this->removeCacheFile();
			}
		}

		function _move_uploaded_file($source, $destination) {
			if(!move_uploaded_file($source, $destination)) {
				switch($_FILES['Filedata']['error']) {
				case 1:
					$this->error_message = 'アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。';
					break;

				case 2:
					$this->error_message = 'アップロードされたファイルは、HTML フォームで指定された MAX_FILE_SIZE を超えています。';
					break;

				case 3:
					$this->error_message = 'アップロードされたファイルは一部のみしかアップロードされていません。';
					break;

				case 4:
					$this->error_message = 'ファイルはアップロードされませんでした。';
					break;

				case 6:
					$this->error_message = 'テンポラリフォルダがありません。PHP 4.3.10 と PHP 5.0.3 で導入されました。';
					break;

				case 7:
					$this->error_message = 'ディスクへの書き込みに失敗しました。PHP 5.1.0 で導入されました。';
					break;

				case 8:
					$this->error_message = 'ファイルのアップロードが拡張モジュールによって停止されました.';
					break;

				default:
					$this->error_message = 'move_uploaded_file error';
					break;
				}
				$this->log->write('ERROR:' . $this->error_message);

				return false;
			}

			return true;
		}

		function _regist($file_name, &$contents_id) {
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
					return $this->update($row, $node_id, $contents_id);
				}
				else {
					return $this->updateNode($row, $node_id, $contents_id);
				}
			}
			else {
				return $this->insert($parent_node, $file_name, 'file', 'leaf', $node_id, $contents_id);
			}
		}

		function update($row, &$node_id, &$contents_id) {
			// start transaction
			$this->db->begin();

			$node_id = $row['node_id'];
			$contents_id = $row['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
			$file_data['node_id'] = $row['node_id'];
			$file_data['contents_id'] = $contents_id;
			$file_data['version_id'] = $this->version['working_version_id'];
			$file_data['revision_id'] = $this->version['revision_id'];
			$file_data['update_user'] = $this->user_id;
			$file_data['update_datetime'] = time();
			$file_data['create_datetime'] = time();

			$ret = $this->resource_node_table->update($file_data);

			if($ret) {
				$this->db->commit();
			}
			if(!$ret) {
				$this->db->rollback();
			}

			return $ret;
		}

		function updateNode($row, &$node_id, &$contents_id) {
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

			if($ret) {
				$this->db->commit();
				$node_id = $new_node_id;
			}
			if(!$ret) {
				$this->db->rollback();
			}

			return $ret;
		}

		function insert($parent_node, $file_name, $node_type, $node_class, &$node_id, &$contents_id) {
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
			$ret = $node->insert($node_type, $node_class, $this->user_id, $new_node_id);
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
				$ret &= $new_node->saveName($file_name, $this->user_id);

				$this->db->commit();
			}
			else {
				$this->db->rollback();
			}
			return $ret;
		}

		function createthumbnail($dir, $file_name, $file_extension, $prefix, $max_size) {
			$source_file_path = $dir . $file_name . '.' . $file_extension;
			switch(strtolower($file_extension)) {
			case 'jpg':
			case 'jpeg':
				if(!function_exists('imagecreatefromjpeg')) return;
				$image = @imagecreatefromjpeg($source_file_path);
				break;

			case 'gif':
				if(!function_exists('imagecreatefromgif')) return;
				$image = @imagecreatefromgif($source_file_path);
				break;

			case 'png':
				if(!function_exists('imagecreatefrompng')) return;
				$image = @imagecreatefrompng($source_file_path);
				break;

			default:
				return;
			}

			$image_size = getimagesize($source_file_path);
			$width = $image_size[0];
			$height = $image_size[1];

			if($width > $max_size) {
				if($width > $height) {
					$height = round($height * $max_size / $width);
					$width = $max_size;
				}
				else {
					$width = round($width * $max_size / $height);
					$height = $max_size;
				}
			}
			else if($height > $max_size) {
				$width = round($width * $max_size / $height);
				$height = $max_size;
			}
			if(!$width) $width=1;
			if(!$height) $height=1;

			$new_image = ImageCreateTrueColor($width, $height);
			ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);

			$thumbnail_file_path = $dir . $prefix . $file_name . '.' . $file_extension;

			switch(strtolower($file_extension)) {
			case 'jpg':
			case 'jpeg':
				ImageJPEG($new_image, $thumbnail_file_path, 100);
				break;

			case 'gif':
				ImageGIF($new_image, $thumbnail_file_path);
				break;

			case 'png':
				ImagePNG($new_image, $thumbnail_file_path);
				break;
			}

			chmod($thumbnail_file_path, 0777);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/upload.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_dialog.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_uploader.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_upload.php');
		}
	}
