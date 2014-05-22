<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_popup extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->_setProperty('target', '');
			$this->_setProperty('target_id', '');

			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, null
									, null
									, null
									, 1
									, null);

			$this->tree->setConfig($this->tree_config);

			if($this->request['node_id']) {
				$this->openCurrentNode($this->request['node_id']);
			}

			$this->status = true;

			if(is_array($_FILES['upload'])) {
				$response['CKEditorFuncNum'] = $this->request['CKEditorFuncNum'];
				$response['url'] = '';
				$response['message'] = 'サーバブラウザを使用してください';

				// HTTPヘッダー出力
				$this->sendHttpHeader();

				// HTMLヘッダー出力
				$this->showHtmlHeader();

				require_once('./view/view_quick_upload.php');
				exit;
			}
		}

		function open() {
			// target
			$this->_setRequest('target');
			$this->_setRequest('target_id');
		}

		function openCurrentNode($node_id) {
			$sql_org = "select parent_node from %VIEW% where node_id='%NODE_ID%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql_org);

			for($id = $node_id; $id && $id != 'root'; $id = $row['parent_node']) {
				$this->session['open_nodes'][$id] = true;

				$sql = str_replace('%NODE_ID%', $id, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}
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
			if($this->request['source_node_id'] && $this->request['source_node_id'] != 'null') {
				if($this->request['destination_node_id'] != 'trash' && $this->tree->checkDuplicateById($this->request['destination_node_id'], $this->request['source_node_id'])) {
					$this->message = '既に存在しています';
					$this->status = false;
				}
				else {
					switch($this->request['mode']) {
					case 'arias':
						$source_node = new B_Node($this->db
												, B_RESOURCE_NODE_TABLE
												, B_WORKING_RESOURCE_NODE_VIEW
												, $this->version['working_version_id']
												, $this->version['revision_id']
												, $this->request['source_node_id']
												, null
												, 'all'
												, $this->session['open_nodes']);

						// start transaction
						$this->db->begin();
						$ret = $source_node->arias($this->request['destination_node_id'], $this->user_id);
						break;

					case 'copy':
						$source_node = new B_Node($this->db
												, B_RESOURCE_NODE_TABLE
												, B_WORKING_RESOURCE_NODE_VIEW
												, $this->version['working_version_id']
												, $this->version['revision_id']
												, $this->request['source_node_id']
												, null
												, 'all'
												, $this->session['open_nodes']);

						// start transaction
						$this->db->begin();

						$ret = $source_node->copy($this->request['destination_node_id'], $this->user_id, array('obj' => $this, 'method' => 'copy_callback'));
						break;

					case 'cut':
						$source_node = new B_Node($this->db
												, B_RESOURCE_NODE_TABLE
												, B_WORKING_RESOURCE_NODE_VIEW
												, $this->version['working_version_id']
												, $this->version['revision_id']
												, $this->request['source_node_id']
												, null
												, 1
												, $this->session['open_nodes']);

						// start transaction
						$this->db->begin();
						$ret = $source_node->move($this->request['destination_node_id'], $this->user_id);
						break;
					}
					if($ret) {
						$this->status = true;
						$this->db->commit();

						// remove cache files
						$this->removeCacheFile();
					}
					else {
						$this->status = false;
						$this->db->rollback();
						$this->message = $this->getErrorMessage($source_node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function copy_callback($param) {
			$node = $param['node'];

			$file = pathinfo($node->node_name);
			$file_name = B_RESOURCE_DIR . $node->node_id . '.' . $file['extension'];
			$new_file_name = B_RESOURCE_DIR . $param['new_node_id'] . '.' . $file['extension'];
			$thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $node->node_id . '.' . $file['extension'];
			$new_thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $param['new_node_id'] . '.' . $file['extension'];

			copy($file_name, $new_file_name);
			copy($thumb_file_name, $new_thumb_file_name);

			return true;
		}

		function createNode() {
			$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			$node = new B_Node($this->db
							, B_RESOURCE_NODE_TABLE
							, B_WORKING_RESOURCE_NODE_VIEW
							, $this->version['working_version_id']
							, $this->version['revision_id']
							, $this->request['destination_node_id']
							, null
							, 1
							, $this->session['open_nodes']);

			$node->setConfig($this->tree_config);

			// start transaction
			$this->db->begin();
			$ret = $node->insert($this->request['node_type'], $this->request['node_class'], $this->user_id, $new_node_id);
			if($ret) {
				$this->status = true;
				$this->db->commit();

				// remove cache files
				$this->removeCacheFile();
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message = $this->getErrorMessage($node->getErrorNo());
			}
			$this->response($new_node_id, 'new_node');
			exit;
		}

		function deleteNode() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$source_node = new B_Node($this->db
										, B_RESOURCE_NODE_TABLE
										, B_WORKING_RESOURCE_NODE_VIEW
										, $this->version['working_version_id']
										, $this->version['revision_id']
										, $this->request['node_id']
										, null
										, 1
										, $this->session['open_nodes']);

				// start transaction
				$this->db->begin();
				$ret = $source_node->move('trash', $this->user_id);
				if($ret) {
					$this->status = true;
					$this->db->commit();

					// remove cache files
					$this->removeCacheFile();
				}
				else {
					$this->status = false;
					$this->db->rollback();
					$this->message = $this->getErrorMessage($source_node->getErrorNo());
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function truncateNode() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_Node($this->db
								, B_RESOURCE_NODE_TABLE
								, B_WORKING_RESOURCE_NODE_VIEW
								, $this->version['working_version_id']
								, $this->version['revision_id']
								, $this->request['node_id']
								, null
								, 'all'
								, $this->session['open_nodes']);

				// start transaction
				$this->db->begin();
				$ret = $node->delete();
				if($ret) {
					$this->status = true;
					$this->db->commit();

					// remove cache files
					$this->removeCacheFile();
				}
				else {
					$this->status = false;
					$this->db->rollback();
					$this->message = $this->getErrorMessage($node->getErrorNo());
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				if(!trim($this->request['node_name'])) {
					$this->status = false;
				}
				else if(strlen($this->request['node_name']) != mb_strlen($this->request['node_name'])) {
					$this->status = false;
					$this->message = '日本語は使用できません';
				}
				else if($this->tree->checkDuplicateByName($this->request['node_id'], $this->request['node_name'])) {
					$this->message = '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。';
					$this->status = false;
				}
				else if(preg_match('/\//', $this->request['node_name'])) {
					$this->message = 'ファイル名、またはフォルダ名に「/」(スラッシュ)は使用できません';
					$this->status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['node_id']
									, null
									, 0
									, $this->session['open_nodes']);

					// start transaction
					$this->db->begin();
					$ret = $node->saveName($this->request['node_name'], $this->user_id);
					if($ret) {
						$this->status = true;
						$this->db->commit();

						// remove cache files
						$this->removeCacheFile();
					}
					else {
						$this->status = false;
						$this->db->rollback();
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function updateDispSeq() {
			if($this->request['parent_node_id'] && $this->request['parent_node_id'] != 'null') {
				if($this->tree->checkDuplicateById($this->request['parent_node_id'], $this->request['source_node_id'])) {
					$this->message = '既に存在しています';
					$this->status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['parent_node_id']
									, null
									, 1
									, $this->session['open_nodes']);

					// start transaction
					$this->db->begin();
					$ret = $node->updateDispSeq($this->request, $this->user_id);
					if($ret) {
						$this->status = true;
						$this->db->commit();

						// remove cache files
						$this->removeCacheFile();
					}
					else {
						$this->status = false;
						$this->db->rollback();
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function download() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_Node($this->db
								, B_RESOURCE_NODE_TABLE
								, B_WORKING_RESOURCE_NODE_VIEW
								, $this->version['working_version_id']
								, $this->version['revision_id']
								, $this->request['node_id']
								, null
								, 'all'
								, $this->session['open_nodes']);

				if($node->node_type == 'folder') {
					if(!class_exists('ZipArchive')) exit;

					$zip = new ZipArchive();
					if($this->request['node_id'] == 'root') {
						$file_name = 'root.zip';
					}
					else {
						$file_name = $node->node_name . '.zip';
					}

					$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

					if(!$zip->open($file_path, ZipArchive::CREATE)) {
						exit;
					}

					$node->serializeForDownload($data);
					foreach($data as $key => $value) {
						$file = B_RESOURCE_DIR . $value;
						$zip->addFile($file, $key);
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
					$info = pathinfo($node->node_name);
					$file_path = B_RESOURCE_DIR . $node->contents_id . '.' . $info['extension'];

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
					header('Content-Disposition: attachment; filename=' . $node->node_name);
					ob_end_clean();
					readfile($file_path);
				}
			}

			exit;
		}

		function response($node_id, $category) {
			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}

			$root_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']);

			$list[] = $root_node->getNodeList($node_id, $category);
			$trash_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'trash'
									, null
									, 0
									, $this->session['open_nodes']
									, 'trash');

			$list[] = $trash_node->getNodeList('', '');

			if(!$this->request['node_id']) {
				$response['current_node'] = $this->session['current_node'];
			}
			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
		}

		function getErrorMessage($error) {
			global $g_data_set, ${$g_data_set};

			return ${$g_data_set}['node_error'][$error];
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/resource.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/resource_tree.css" type="text/css" rel="stylesheet" media="all" />');
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
