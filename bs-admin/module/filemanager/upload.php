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

		function upload() {
			switch($this->request['mode']) {
			case 'confirm':
			case 'overwrite':
				$this->_confirm($this->request['mode']);
				break;

			default:
				$this->_upload();
			}
		}

		function _confirm($mode) {
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
				$file = $this->util->pathinfo($_POST['filename']);
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

	 			// confirm overwrite
				if($mode == 'confirm'){
					if(file_exists(B_UPLOAD_DIR . $path . $file['basename'])) {
						$message = $file['basename'] . 'は既に存在します。<br />上書きしてもよろしいですか？';
						$response_mode = 'confirm';
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

		function _upload() {
			$status = true;

			try {
				// set path
				if($this->global_session[$this->session['relation']]['current_node'] != 'root') {
					$path = $this->global_session[$this->session['relation']]['current_node'] . '/';
					if(substr($path, 0, 1) == '/') {
						$path = substr($path, 1);
					}
				}

				// get file info
				$file = $this->util->pathinfo($_FILES['Filedata']['name']);

				if(strtolower($file['extension']) == 'zip' && class_exists('ZipArchive')) {
					$zip_file = B_RESOURCE_WORK_DIR . $file['basename'];
					$status = move_uploaded_file($_FILES['Filedata']['tmp_name'], $zip_file);
					if($status) {
						$zip = new ZipArchive();
						$zip->open($zip_file);
						$zip->extractTo(B_UPLOAD_DIR . $path);
						$zip->close();
						unlink($zip_file);
						$this->removeThumbnailCacheFile();
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);
					}
				}
				else {
					$status = move_uploaded_file($_FILES['Filedata']['tmp_name'], B_UPLOAD_DIR . $path . $file['basename']);
					if($status) {
						chmod(B_UPLOAD_DIR . $path . $file['basename'], 0777);
						$this->removeThumbnail($path, $file['basename']);
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);
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
