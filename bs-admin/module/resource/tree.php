<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

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
									, 0
									, null);

			$this->tree->setConfig($this->tree_config);

			if($this->request['node_id']) {
				$this->openCurrentNode($this->request['node_id']);
			}

			if(!$this->session['sort_order']) $this->session['sort_order'] = 'asc';
			if(!$this->session['sort_key']) $this->session['sort_key'] = 'node_name';

			$this->status = true;
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
			if($this->request['sort_key']) {
				if($this->request['node_id'] == $this->session['current_node'] && $this->session['sort_key'] == $this->request['sort_key']) {
					$this->session['sort_order'] = $this->session['sort_order'] == 'asc' ? 'desc' : 'asc';
				}
				
				$this->session['sort_key'] = $this->request['sort_key'];
			}
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
			echo json_encode($response);

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
						// start transaction
						$this->db->begin();
						foreach($this->request['source_node_id'] as $node_id) {
							$source_node = new B_Node($this->db
													, B_RESOURCE_NODE_TABLE
													, B_WORKING_RESOURCE_NODE_VIEW
													, $this->version['working_version_id']
													, $this->version['revision_id']
													, $node_id
													, null
													, 'all'
													, null);

							$ret = $source_node->arias($this->request['destination_node_id'], $this->user_id);
						}
						break;

					case 'copy':
						// start transaction
						$this->db->begin();
						foreach($this->request['source_node_id'] as $node_id) {
							$source_node = new B_Node($this->db
													, B_RESOURCE_NODE_TABLE
													, B_WORKING_RESOURCE_NODE_VIEW
													, $this->version['working_version_id']
													, $this->version['revision_id']
													, $node_id
													, null
													, 'all'
													, null);

							$ret = $source_node->copy($this->request['destination_node_id'], $this->user_id, $new_node_id, array('obj' => $this, 'method' => 'copy_callback'));
							if(!$ret) break;
							$this->selected_node[] = $new_node_id[0];
							$new_node_id = '';
						}
						break;

					case 'cut':
						// start transaction
						$this->db->begin();
						foreach($this->request['source_node_id'] as $node_id) {
							$source_node = new B_Node($this->db
													, B_RESOURCE_NODE_TABLE
													, B_WORKING_RESOURCE_NODE_VIEW
													, $this->version['working_version_id']
													, $this->version['revision_id']
													, $node_id
													, null
													, 0
													, null);

							$ret = $source_node->move($this->request['destination_node_id'], $this->user_id);
							if(!$ret) break;
						}
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
						$node = $source_node ? $source_node : $destination_node;
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function copy_callback(&$node) {
			if($node->node_type != 'file') return true;

			$tbl_node = new B_Table($this->db, B_RESOURCE_NODE_TABLE);

			// new_contents_id
			$new_contents_id = $tbl_node->selectMaxValuePlusOne('node_id');
			$node->new_contents_id = $new_contents_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];

			// copy
			$file = pathinfo($node->node_name);
			$file_name = B_RESOURCE_DIR . $node->contents_id . '.' . $file['extension'];
			$new_file_name = B_RESOURCE_DIR . $node->new_contents_id . '.' . $file['extension'];
			copy($file_name, $new_file_name);

			// copy thumbnail
			switch(strtolower($file['extension'])) {
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
			case 'bmp':
				$thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $node->contents_id . '.' . $file['extension'];
				$new_thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $node->new_contents_id . '.' . $file['extension'];
				copy($thumb_file_name, $new_thumb_file_name);
				break;
			}

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
							, null);

			$node->setConfig($this->tree_config);

			// start transaction
			$this->db->begin();
			$ret = $node->insert($this->request['node_type'], $this->request['node_class'], $this->user_id, $new_node_id, $new_node_name);
			if($ret) {
				$this->status = true;
				$this->db->commit();
				if($this->request['node_type'] == 'file') {
					// set contents_id
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

					$file_info = pathinfo($new_node_name);
					$file_name = B_RESOURCE_DIR . $new_node_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'] . '.' . $file_info['extension'];
					$fp = fopen($file_name, 'w');
					fclose($fp);
				}
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
			if($this->request['delete_node_id'] && $this->request['delete_node_id'] != 'null') {
				// start transaction
				$this->db->begin();

				$disp_seq = 0;
				foreach($this->request['delete_node_id'] as $node_id) {
					$source_node = new B_Node($this->db
											, B_RESOURCE_NODE_TABLE
											, B_WORKING_RESOURCE_NODE_VIEW
											, $this->version['working_version_id']
											, $this->version['revision_id']
											, $node_id
											, null
											, 0
											, null);

					$ret = $source_node->move('trash', $this->user_id, $disp_seq);
					$disp_seq++;
					if(!$ret) break;
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
								, null);

				// ブラウザを閉じても処理を継続
				ignore_user_abort(true);

				// set time limit to 10 minutes
				set_time_limit(600);

				// start transaction
				$this->db->begin();
				$ret = $node->delete();
				if($ret) {
					$this->status = true;
					$this->db->commit();

					// delete useless files
					$this->truncateFiles();

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

		function truncateFiles() {
			$sql = "select contents_id, count(*) cnt, max(del_flag) del_flag, node_name
					from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
					where node_type = 'file'
					group by contents_id
					having cnt = 1 and del_flag = '1'";
			$rs = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($rs)) {
				$info = pathinfo($row['node_name']);
				$file_name = B_RESOURCE_DIR . $row['contents_id'] . '.' . strtolower($info['extension']);
				switch($info['extension']) {
				case 'avi':
				case 'flv':
				case 'mp4':
				case 'mpg':
				case 'mpeg':
				case 'wmv':
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.jpg';
					break;

				default:
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.' . strtolower($info['extension']);
					break;
				}
				if(file_exists($file_name)) {
					unlink($file_name);
				}
				if(file_exists($thumb_file_name)) {
					unlink($thumb_file_name);
				}
			}
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node_name = trim($this->request['node_name']);
				if($this->checkFileName($this->request['node_id'], $node_name)) {
					$node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['node_id']
									, null
									, 0
									, null);

					$old_name = $node->node_name;

					// start transaction
					$this->db->begin();

					$ret = $node->saveName($node_name, $this->user_id);

					$contents_id = $this->request['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
					$request_info = pathinfo($node_name);
					$old_name_info = pathinfo($old_name);

					if($ret) {
						if($request_info['extension'] != $old_name_info['extension']) {
							if($node->contents_id == $contents_id) {
								// change extension
								$ret = rename(B_RESOURCE_DIR . $node->contents_id . '.' . $old_name_info['extension'],
												B_RESOURCE_DIR . $node->contents_id . '.' . $request_info['extension']);
								$thumbnail = B_RESOURCE_DIR . B_THUMB_PREFIX . $node->contents_id . '.' . $old_name_info['extension'];
								if(file_exists($thumbnail)) {
									$ret = rename($thumbnail, B_RESOURCE_DIR . B_THUMB_PREFIX . $node->contents_id . '.' . $request_info['extension']);
								}
							}
							else {
								$old_contents_id = $node->contents_id;
								$ret = $node->setContentsId($contents_id, $this->user_id);
								// change extension
								$ret = copy(B_RESOURCE_DIR . $old_contents_id . '.' . $old_name_info['extension'],
												B_RESOURCE_DIR . $contents_id . '.' . $request_info['extension']);

								$thumbnail = B_RESOURCE_DIR . B_THUMB_PREFIX . $old_contents_id . '.' . $old_name_info['extension'];
								if(file_exists($thumbnail)) {
									$ret = copy($thumbnail, B_RESOURCE_DIR . B_THUMB_PREFIX . $contents_id . '.' . $request_info['extension']);
								}
							}
						}
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
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function checkFileName($node_id, $file_name) {
			$file_info = pathinfo($file_name);
			if(!strlen(trim($file_name))) {
				$this->message = '名前を入力してください。';
				return false;
			}
			if(strlen($file_name) != mb_strlen($file_name)) {
				$this->message = '日本語は使用できません';
				return false;
			}
			if($this->tree->checkDuplicateByName($node_id, $file_name)) {
				$this->message = '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。';
				return false;
			}
			if(substr($file_name, -1) == '.') {
				$this->message = '拡張子が必要です。';
				return false;
			}
			if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file_name)) {
				$this->message = 'ファイル名／フォルダ名に次の文字は使えません \ / : * ? " < > | スペース';
				return false;
			}

			return true;
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
									, null);

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
			if($this->request['download_node_id'] && $this->request['download_node_id'] != 'null') {
				foreach($this->request['download_node_id'] as $node_id) {
					$nodes[] = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $node_id
									, null
									, 'all'
									, null);
				}
				if(count($nodes) == 1 && $nodes[0]->node_type == 'file') {
					$info = pathinfo($nodes[0]->node_name);
					$file_path = B_RESOURCE_DIR . $nodes[0]->contents_id . '.' . $info['extension'];

					// ダウンロード
					header('Pragma: cache;');
					header('Cache-Control: public');

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
					header('Content-Disposition: attachment; filename=' . $nodes[0]->node_name);
					ob_end_clean();
					readfile($file_path);
				}
				else {
					if(!class_exists('ZipArchive')) exit;

					$zip = new ZipArchive();

					if(count($nodes) == 1) {
						if($this->request['download_node_id'][0] == 'root') {
							$file_name = 'root.zip';
						}
						else {
							$file_name = $nodes[0]->node_name . '.zip';
						}
					}
					else {
						$file_name = 'resources.zip';
					}

					$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

					if(!$zip->open($file_path, ZipArchive::CREATE)) {
						exit;
					}

					foreach($nodes as $node) {
						$node->serializeForDownload($data);
						foreach($data as $key => $value) {
							if($value) {
								$file = B_RESOURCE_DIR . $value;
								$zip->addFile($file, $key);
							}
							else {
								$zip->addEmptyDir($key);
							}
						}
					}
					$zip->close();

					// ダウンロード
					header('Pragma: cache;');
					header('Cache-Control: public');
					header('Content-type: application/x-zip-dummy-content-type');
					header('Content-Disposition: attachment; filename=' . $file_name);
					ob_end_clean();
					readfile($file_path);

					// 削除
					unlink($file_path);
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
									, $this->session['open_nodes']
									, 'auto');

			if($this->session['sort_key']) {
				$current_node = $root_node->getNodeById($this->session['current_node']);
				if($current_node) {
					$current_node->setSortKey($this->session['sort_key']);
					$current_node->setSortOrder($this->session['sort_order']);
				}
			}

			$list[] = $root_node->getNodeList($node_id, $category, B_RESOURCE_DIR);
			$trash_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'trash'
									, null
									, 0
									, $this->session['open_nodes']
									, 'trash'
									, 'auto');

			if($this->session['sort_key']) {
				$current_node = $trash_node->getNodeById($this->session['current_node']);
				if($current_node) {
					$current_node->setSortKey($this->session['sort_key']);
					$current_node->setSortOrder($this->session['sort_order']);
				}
			}

			$list[] = $trash_node->getNodeList('', '', B_RESOURCE_DIR);

			if(!$this->request['node_id']) {
				$response['current_node'] = $this->session['current_node'];
			}

			if($this->selected_node) {
				$response['selected_node'] = $this->selected_node;
			}

			if($list) {
				$response['node_info'] = $list;
			}
			if($this->session['sort_key']) {
				$response['sort_key'] = $this->session['sort_key'];
				$response['sort_order'] = $this->session['sort_order'];
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
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
			$this->html_header->appendProperty('css', '<link href="css/selectbox.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_index.php');
		}
	}
