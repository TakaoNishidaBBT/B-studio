<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_popup extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->dir = B_UPLOAD_DIR;

			$this->_setProperty('window', '');
			$this->_setProperty('target', '');
			$this->_setProperty('target_id', '');
			$this->_setProperty('width', '');
			$this->_setProperty('height', '');

			require_once('./config/popup_tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_FileNode($this->dir, '');

			$this->tree->setConfig($this->tree_config);

			$this->status = true;

			if(is_array($_FILES['upload'])) {
				$this->upload(); 
				exit;
			}
		}

		function open() {
			// target
			$this->_setRequest('window');
			$this->_setRequest('target');
			$this->_setRequest('target_id');
			$this->_setRequest('width');
			$this->_setRequest('height');
		}

		function upload() {
			$status = true;

			// アップロードファイル名取得
			$file = $this->util->pathinfo($_FILES['upload']['name']);
			if($status) {
				if(strlen($file['basename']) != mb_strlen($file['basename'])) {
					$this->error_message = "日本語ファイル名は使用できません。";
					$status = false;
				}

				if(!preg_match('/[0-9a-zA-Z\-\_^]/',$file['basename'])){
					$this->error_message = "ファイル名が不正です。";
					$status = false;
				}
			}

			if($status) {
				if($this->global_session[$this->session['relation']]['current_node'] != 'root') {
					$path = $this->global_session[$this->session['relation']]['current_node'] . '/';
					if(substr($path, 0, 1) == '/') {
						$path = substr($path, 1);
					}
				}
				if(strtolower($file['extension']) == 'zip' && class_exists('ZipArchive')) {
					$zip_file = B_RESOURCE_WORK_DIR . $file['basename'];
					$status = move_uploaded_file($_FILES['upload']['tmp_name'], $zip_file);
					if($status) {
						$zip = new ZipArchive();
						$zip->open($zip_file);
						$zip->extractTo(B_UPLOAD_DIR . $path);
						$zip->close();
						unlink($zip_file);
						$this->removeThumbnailCacheFile();
					}
				}
				else {
					$status = move_uploaded_file($_FILES['upload']['tmp_name'], B_UPLOAD_DIR . $path . $file['basename']);
					if($status) {
						chmod(B_UPLOAD_DIR . $path . $file['basename'], 0777);
						$i = strrpos($file['basename'], '.');
						if($i) {
							$file_name = substr($file['basename'], 0, $i);
							$file_extension = substr($file['basename'], $i+1);
						}
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);
					}
				}
				if(!$status) {
					switch($_FILES['upload']['error']) {
					case 1:
						$this->error_message = "アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。";
						break;

					case 2:
						$this->error_message = "アップロードされたファイルは、HTML フォームで指定された MAX_FILE_SIZE を超えています。";
						break;

					case 3:
						$this->error_message = "アップロードされたファイルは一部のみしかアップロードされていません。";
						break;

					case 4:
						$this->error_message = "ファイルはアップロードされませんでした。";
						break;

					case 6:
						$this->error_message = "テンポラリフォルダがありません。PHP 4.3.10 と PHP 5.0.3 で導入されました。";
						break;

					case 7:
						$this->error_message = "ディスクへの書き込みに失敗しました。PHP 5.1.0 で導入されました。";
						break;

					case 8:
						$this->error_message = "ファイルのアップロードが拡張モジュールによって停止されました.";
						break;

					default:
						$this->error_message = "エラー";
						break;
					}

					$this->log->write($this->error_messasge);
				}
			}

			$response['CKEditorFuncNum'] = $this->request['CKEditorFuncNum'];
			if($status) {
				$response['url'] = B_UPLOAD_URL . $path . $file['basename'];
			}
			else {
				$response['message'] = $this->error_message;
			}
			$this->response_quick_upload($response);
			exit;
		}

		function refleshThumnailCache($root) {
			$max = $root->getMaxThumbnailNo();
			$root->createthumbnail($data, $max);
			$fp = fopen(B_FILE_INFO_THUMB, 'w+');
	        fwrite($fp, serialize($data));
			fclose($fp);
		}

		function getNodeList() {
			if($this->request['node_id']) {
				$this->session['current_node'] = $this->request['node_id'];
				$this->session['open_nodes'][$this->request['node_id']] = true;
			}
			if(!$this->session['current_node']) {
				$this->session['current_node'] = 'root';
			}
			if(isset($this->request['disp_mode'])) {
				$this->session['disp_mode'] = $this->request['disp_mode'];
			}
			$this->response($this->session['current_node'], 'select');

			exit;
		}

		function closeNode() {
			$this->session['open_nodes'][$this->request['node_id']] = false;

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo $this->util->json_encode($response);

			exit;
		}

		function pasteNode() {
			$source = new B_FileNode($this->dir, $this->request['source_node_id']);
			$dest = new B_FileNode($this->dir, $this->request['destination_node_id']);

			if(!file_exists($source->fullpath)) {
				$this->message = '他のユーザに更新されています';
				$this->status = false;
			}
			if(!file_exists($dest->fullpath)) {
				$this->message = '他のユーザに更新されています';
				$this->status = false;
			}
			else if(file_exists($dest->fullpath . '/' . $source->file_name)) {
				$this->message = '既に存在しています';
				$this->status = false;
			}
			else {
				if($dest->node_type == 'folder' || $dest->node_type == 'root') {
					$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
					$ret = $root->rename($source->path, B_Util::getPath($dest->path, $source->file_name));
				}
				if($ret) {
					$this->status = true;
					$this->session['open_nodes'][$this->request['source_node_id']] = false;
					$this->refleshThumnailCache($root);
				}
				else {
					$this->status = false;
					$this->message = 'エラーが発生しました';
				}
			}

			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function createNode() {
			$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			$node = new B_FileNode($this->dir, $this->request['destination_node_id']);

			$ret = $node->createFolder('newFolder', $new_node_id);
			if($ret) {
				$this->status = true;
				$this->session['selected_node'] = $new_node_id;
				$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			}
			else {
				$this->status = false;
				$this->message = 'エラーが発生しました';
			}

			$this->response($new_node_id, 'new_node');
			exit;
		}

		function deleteNode() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_FileNode($this->dir, $this->request['node_id'], null, null, 'all');
				if(!file_exists($node->fullpath)) {
					$this->message = '他のユーザに更新されています';
					$this->status = false;
				}
				else {
					$ret = $node->remove();
					if($ret) {
						$this->status = true;
						$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
						$this->refleshThumnailCache($root);
					}
					else {
						$this->status = false;
						$this->message = 'エラーが発生しました';
					}
				}
			}

			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$file_info = pathinfo($this->request['node_id']);
				$new_name = B_Util::getPath(B_UPLOAD_DIR , B_Util::getPath($file_info['dirname'], $this->request['node_name']));

				if(strlen($this->request['node_name']) != mb_strlen($this->request['node_name'])) {
					$this->status = false;
					$this->message = '日本語は使用できません';
				}
				else if(preg_match('/\//', $this->request['node_name'])) {
					$this->message = 'ファイル名、またはフォルダ名に「/」(スラッシュ)は使用できません';
					$this->status = false;
				}
				else if(!file_exists(B_Util::getPath(B_UPLOAD_DIR, $this->request['node_id']))) {
					$this->status = false;
					$this->message = '他のユーザに更新されています';
				}
				else if(file_exists($new_name) && strtolower($file_info['filename']) != strtolower($this->request['node_name'])) {
					$this->status = false;
					$this->message = '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。';
				}
				else {
					$ret =  rename(B_Util::getPath(B_UPLOAD_DIR, $this->request['node_id']), $new_name);
					if($ret) {
						$this->status = true;
						$dir = $file_info['dirname'] == '.' ? '' : $file_info['dirname'] . '/';
						$this->session['open_nodes'][$dir . $this->request['node_name']] = true;
						$this->removeThumbnailCacheFile();
					}
					else {
						$this->message = '名前を変更できません。';
					}
				}
			}

			$this->response($this->session['current_node'], 'select');
			exit;
		}

		function download() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_FileNode($this->dir, $this->request['node_id'], null, null, 'all');
				if($node->node_type == 'folder' || $node->node_type == 'root') {
					if(!class_exists('ZipArchive')) exit;

					$zip = new ZipArchive();
					if($this->request['node_id'] == 'root') {
						$file_name = 'root.zip';
					}
					else {
						$file_name = $node->file_name . '.zip';
					}

					$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

					if(!$zip->open($file_path, ZipArchive::CREATE)) {
						exit;
					}

					$node->serializeForDownload($data);
					foreach($data as $key => $value) {
						if($value) {
							$zip->addFile($value, $key);
						}
						else {
							$zip->addEmptyDir($key);
						}
					}

					$zip->close();

					// ダウンロード
					header("Pragma: cache;");
					header("Cache-Control: public");
					header('Content-type: application/x-zip-dummy-content-type');
					header('Content-Disposition: attachment; filename=' . $file_name);
					ob_end_clean();
					readfile($file_path);

					// 削除
					unlink($file_path);
				}
				else {
					$info = pathinfo($node->file_name);

					// ダウンロード
					header("Pragma: cache;");
					header("Cache-Control: public");

					switch(strtolower($info['extension'])) {
					case 'swf':
						header('Content-type: application/x-shockwave-flash');
						break;

					case 'css':
						header('Content-Type: text/css; charset=' . B_CHARSET);
						break;

					case 'js':
						header('Content-type: application/x-javascript');
						break;

					default:
						header('Content-Type: image/' . strtolower($info['extension']));
						break;
					}
					header('Content-Disposition: attachment; filename=' . $node->file_name);
					ob_end_clean();
					readfile($node->fullpath);
				}
			}

			exit;
		}

		function response($node_id, $category) {
			// if thumb-nail cache file not exists
			if(!file_exists(B_FILE_INFO_THUMB)) {
				$this->createThumbnailCacheFile();
			}

			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}
			$root_node = new B_FileNode($this->dir, '/', $this->session['open_nodes'], null, 1);
			$list[] = $root_node->getNodeList($node_id, $category);

			if(!$this->request['node_id']) {
				$response['current_node'] = $this->session['current_node'];
			}

			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
		}

		function response_quick_upload($response) {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_quick_upload.php');
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/filemanager_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/filemanager.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/modal_window.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_modal_window.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_index.php');
		}
	}
