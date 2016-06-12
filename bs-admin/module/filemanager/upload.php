<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_upload extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);
		}

		function init() {
			$this->session['relation'] = $this->request['session'];
		}

		function confirm() {
			$status = true;

			try {
	 			// file size check
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
					throw new Exception('ファイルサイズが大きすぎます。アップロードできるのは' . $limit . 'までです');
				}

	 			// check file name
				$file = pathinfo($_POST['filename']);
				if(strlen($file['basename']) != mb_strlen($file['basename'])) {
					throw new Exception('日本語ファイル名は使用できません。');
				}
				if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file['basename'])) {
					throw new Exception('ファイル名／フォルダ名に次の文字は使えません \ / : * ? " < > | スペース');
				}
				if($this->global_session[$this->session['relation']]['current_node'] != 'root') {
					$path = $this->global_session[$this->session['relation']]['current_node'] . '/';
					if(substr($path, 0, 1) == '/') {
						$path = substr($path, 1);
					}
				}

				if($file['extension'] == 'zip') {
					switch($this->request['extract_mode']) {
					case 'confirm':
						$response_mode = 'zipConfirm';
						$message = $file['basename'] . 'を展開しますか？';
						break;

					case 'noextract':
						if(file_exists(B_UPLOAD_DIR . $path . $file['basename']) && $this->request['mode'] == 'confirm') {
							$response_mode = 'confirm';
							$message = $file['basename'] . 'は既に存在します。<br />上書きしてもよろしいですか？';
						}
						break;
					}
				}
				else {
					if($this->request['mode'] == 'confirm' && file_exists(B_UPLOAD_DIR . $path . $file['basename'])) {
						$response_mode = 'confirm';
						$message = $file['basename'] . 'は既に存在します。<br />上書きしてもよろしいですか？';
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

		function upload() {
			$status = true;

			try {
				// set path
				if($this->global_session[$this->session['relation']]['current_node'] != 'root') {
					$this->path = $this->global_session[$this->session['relation']]['current_node'] . '/';
					if(substr($this->path, 0, 1) == '/') {
						$this->path = substr($this->path, 1);
					}
				}

				// get file info
				$file = pathinfo($_FILES['Filedata']['name']);

				if(strtolower($file['extension']) == 'zip' && class_exists('ZipArchive') && $this->request['extract_mode'] == 'extract') {
					// set time limit to 10 minutes
					set_time_limit(600);

					// continue whether a client disconnect or not
					ignore_user_abort(true);

					// check zip file inside
					$this->checkZipFile($_FILES['Filedata']['tmp_name']);

					$zip_file = B_RESOURCE_WORK_DIR . $file['basename'];
					$status = move_uploaded_file($_FILES['Filedata']['tmp_name'], $zip_file);

					if($status) {
						$zip = new ZipArchive();
						$zip->open($zip_file);
						$zip->extractTo(B_RESOURCE_EXTRACT_DIR);
						$node = new B_FileNode(B_RESOURCE_EXTRACT_DIR, '/', null, null, 'all');
						$node->walk($this, regist_archive);

						$zip->close();
						unlink($zip_file);
						$this->removeThumbnailCacheFile();
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);

						foreach($this->registered_archive_node as $path) {
							$node = new B_FileNode(B_UPLOAD_DIR, $path, null, null, 1);
							$response['node_info'][] = $node->getNodeList('', '');
						}
$this->log->write('node_info', $response['node_info']);
					}
				}
				else {
					$status = move_uploaded_file($_FILES['Filedata']['tmp_name'], B_UPLOAD_DIR . $this->path . $file['basename']);
					if($status) {
						chmod(B_UPLOAD_DIR . $this->path . $file['basename'], 0777);
						$this->removeThumbnail($this->path, $file['basename']);
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);
						$node = new B_FileNode(B_UPLOAD_DIR, $this->path . $file['basename'], null, null, 1);
						$response['node_info'][] = $node->getNodeList('', '');
					}
				}
				if(!$status) {
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
						$this->error_message = 'エラー';
						break;
					}

					$this->log->write($this->error_message);
					throw new Exception($this->error_message);
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

		function checkZipFile($zip_file) {
			$zip = new ZipArchive();
			$zip->open($zip_file);

			for($i=0; $i < $zip->numFiles; $i++) {
				$stat = $zip->statIndex($i);
				$file_name = mb_convert_encoding($stat['name'], 'UTF-8', 'auto');
				if(strlen($file_name) != mb_strlen($file_name)) {
					throw new Exception('日本語ファイル名は使用できません。（zipファイル中）');
				}
			}
		}

		function regist_archive($node) {
			if(!$node->parent || $node->parent->path != '/') return;

			rename($node->fullpath, B_UPLOAD_DIR . $this->path . $node->filename);
			if(!$node->parent->db_node_id) {
				$this->registered_archive_node[] = $this->path . $node->filename;
			}
		}

		function removeThumbnail($path, $filename) {
			$thumb = B_CURRENT_ROOT . B_UPLOAD_FILES . $path . B_THUMB_PREFIX . $filename;
			if(file_exists(B_FILE_INFO_THUMB)) {
				$serializedString = file_get_contents(B_FILE_INFO_THUMB);
			    $thumb_info = unserialize($serializedString);
				$thumb_file = $thumb_info[$thumb];
				if($thumb_file && file_exists(B_UPLOAD_THUMBDIR . $thumb_file)) {
					unlink(B_UPLOAD_THUMBDIR . $thumb_file);
				}
			}
		}

		function refleshThumnailCache($root) {
			$max = $root->getMaxThumbnailNo();
			$root->createthumbnail($data, $max);
			$fp = fopen(B_FILE_INFO_THUMB, 'w+');
	        fwrite($fp, serialize($data));
			fclose($fp);
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
