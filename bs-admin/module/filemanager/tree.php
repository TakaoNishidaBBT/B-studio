<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->dir = B_UPLOAD_DIR;

			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_FileNode($this->dir, '');

			$this->tree->setConfig($this->tree_config);

			$this->status = true;
		}

		function getNodeList() {
			$this->session['selected_node'] = '';

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
			$this->session['selected_node'] = '';

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo $this->util->json_encode($response);

			exit;
		}

		function pasteNode() {
			$dest = new B_FileNode($this->dir, $this->request['destination_node_id']);
			foreach($this->request['source_node_id'] as $node_id) {
				$source = new B_FileNode($this->dir, $node_id);

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
						if($this->session['current_node'] == $this->request['node_id']) {
							$this->session['current_node'] = $new_node_id;
						}
						$this->session['open_nodes'][$this->request['node_id']] = false;
						$this->session['open_nodes'][$new_node_id] = true;
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
			if($this->request['delete_node_id'] && $this->request['delete_node_id'] != 'null') {
				foreach($this->request['delete_node_id'] as $node_id) {
					$node = new B_FileNode($this->dir, $node_id, null, null, 'all');
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
			}

			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$file_info = B_Util::pathinfo($this->request['node_id']);
				$node_name = trim($this->request['node_name']);
				$new_node_id = B_Util::getPath($file_info['dirname'], $node_name);
				$path = B_Util::getPath(B_UPLOAD_DIR , $new_node_id);

				if($this->checkFileName($path, $node_name, $file_info)) {
					$root = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
					$ret = $root->rename($this->request['node_id'], $new_node_id);
					if($ret) {
						$this->status = true;
						$this->session['selected_node'] = $new_node_id;
						if($this->session['current_node'] == $this->request['node_id']) {
							$this->session['current_node'] = $new_node_id;
						}
						$this->session['open_nodes'][$this->request['node_id']] = false;
						$this->session['open_nodes'][$new_node_id] = true;
						$this->refleshThumnailCache($root);
					}
					else {
						$this->message = '名前を変更できません。';
					}
				}
			}
			$this->response($this->session['current_node'], 'select');
			exit;
		}

		function checkFileName($path, $file_name, $file_info) {
			if(!strlen(trim($file_name))) {
				$this->message = '名前を入力してください。';
				return false;
			}
			if(strlen($file_name) != mb_strlen($file_name)) {
				$this->message = '日本語は使用できません';
				return false;
			}
			if(!file_exists(B_Util::getPath(B_UPLOAD_DIR, $node_id))) {
				$this->message = '他のユーザに更新されています';
				return false;
			}
			if(file_exists($path) && strtolower($file_info['basename']) != strtolower($file_name)) {
				$this->message = '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。';
				return false;
			}
			if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file_name)) {
				$this->message = 'ファイル名／フォルダ名に次の文字は使えません \ / : * ? " < > | スペース';
				return false;
			}

			return true;
		}

		function refleshThumnailCache($root) {
			$root->createthumbnail($data);
			$fp = fopen(B_FILE_INFO_THUMB, 'w+');
	        fwrite($fp, serialize($data));
			fclose($fp);
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
					readfile($file_path);

					// 削除
					unlink($file_path);
				}
				else {
					$info = B_Util::pathinfo($node->file_name);

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
			if($this->session['selected_node']) {
				$response['selected_node'] = $this->session['selected_node'];
			}

			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/filemanager_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/filemanager.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_index.php');
		}
	}
