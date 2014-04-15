<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, null
									, null
									, null
									, 1
									, null);

			$this->tree->setConfig($this->tree_config);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css'
					, '<link href="css/template_tree.css" type="text/css" rel="stylesheet" media="all">');
			$this->html_header->appendProperty('script'
					, '<script src="js/bframe_tree.js" type="text/javascript"></script>');

			$this->showHtmlHeader();

			require_once('./view/view_tree.php');
		}

		function getNodeList() {
			$this->session['open_nodes'][$this->request['node_id']] = true;
			$this->response($this->request['node_id'], 'select');
			exit;

		}

		function closeNode() {
			$this->session['open_nodes'][$this->request['node_id']] = false;

			header('Content-Type: application/x-javascript charset=utf-8');
			$param['status'] = true;
			echo $this->util->json_encode($param);
			exit;
		}

		function pasteNode() {
			if($this->request['source_node_id'] && $this->request['source_node_id'] != 'null') {
				if($this->request['mode'] == 'cut' && $this->request['destination_node_id'] != 'trash' &&
					$this->tree->checkDuplicateById($this->request['destination_node_id'], $this->request['source_node_id'])) {

					$this->message = '既に存在しています';
					$status = false;
				}
				else {
					$source_node = new B_Node($this->db
											, B_TEMPLATE_NODE_TABLE
											, B_WORKING_TEMPLATE_NODE_VIEW
											, $this->version['working_version_id']
											, $this->version['revision_id']
											, $this->request['source_node_id'][0]
											, null
											, 'all'
											, $this->session['open_nodes']);

					// start transaction
					$this->db->begin();

					switch($this->request['mode']) {
					case 'arias':
						$ret = $source_node->arias($this->request['destination_node_id'], $this->user_id);
						break;

					case 'copy':
						$callback = array('obj' => $this, 'method' => '_callback_copy');
						$ret = $source_node->copy($this->request['destination_node_id'], $this->user_id, $callback);
						break;

					case 'cut':
						$ret = $source_node->move($this->request['destination_node_id'], $this->user_id);
						break;
					}
					if($ret) {
						$this->db->commit();
						$this->status = true;
					}
					else {
						$this->db->rollback();
						$this->status = false;
						$this->message = $this->getErrorMessage($source_node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function createNode() {
			$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			$node = new B_Node($this->db
							, B_TEMPLATE_NODE_TABLE
							, B_WORKING_TEMPLATE_NODE_VIEW
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
				$this->db->commit();
				$this->status = true;
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
				$source_node = new B_Node($this->db
										, B_TEMPLATE_NODE_TABLE
										, B_WORKING_TEMPLATE_NODE_VIEW
										, $this->version['working_version_id']
										, $this->version['revision_id']
										, $this->request['delete_node_id'][0]
										, null
										, 1
										, $this->session['open_nodes']);

				// start transaction
				$this->db->begin();
				$ret = $source_node->move('trash', $this->user_id);
				if($ret) {
					$this->db->commit();
					$this->status = true;
				}
				else {
					$this->db->rollback();
					$this->status = false;
					$this->message = $this->getErrorMessage($source_node->getErrorNo());
				}
			}
			$this->response($this->request['delete_node_id'], 'select');
			exit;
		}

		function truncateNode() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_Node($this->db
								, B_TEMPLATE_NODE_TABLE
								, B_WORKING_TEMPLATE_NODE_VIEW
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
					$this->db->commit();
					$this->status = true;
				}
				else {
					$this->db->rollback();
					$this->status = false;
					$this->message = $this->getErrorMessage($node->getErrorNo());
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				if(!strlen(trim($this->request['node_name']))) {
					$this->status = false;
					$this->message = '名前を入力してください。';
				}
				else if(strlen($this->request['node_name']) != mb_strlen($this->request['node_name'])) {
					$this->status = false;
					$this->message = '日本語は使用できません';
				}
				else if($this->tree->checkDuplicateByName($this->request['node_id'], $this->request['node_name'])) {
					$this->message = '名前を変更できません。指定されたファイル名は既に存在します。別の名前を指定してください。';
					$status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
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
						$this->db->commit();
						$this->status = true;
					}
					else {
						$this->db->rollback();
						$this->status = false;
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
					$status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
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
						$this->db->commit();
						$this->status = true;
					}
					else {
						$this->db->rollback();
						$this->status = false;
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function response($node_id, $category) {
			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}
			$root_node = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']);

			$list[] = $root_node->getNodeList($node_id, $category);

			$trash_node = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'trash'
									, null
									, 0
									, $this->session['open_nodes']
									, 'trash');

			$list[] = $trash_node->getNodeList('', '');

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

		function _callback_copy(&$node) {
			if(!trim($node->contents_id)) {
				return true;
			}
			$ret = $this->copyTemplate($node->contents_id
									, $node->version
									, $node->revision
									, $new_contents_id);

			$node->new_contents_id = $new_contents_id;

			return $ret;
		}

		function copyTemplate($contents_id, $version_id, $revision_id, &$new_contents_id) {
			$table_template = new B_Table($this->db, B_TEMPLATE_TABLE);

			$sql = "select * from " . B_DB_PREFIX . B_WORKING_TEMPLATE_VIEW . " where contents_id='" . $contents_id . "'";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			$row['contents_id'] = '';
			$row['version_id'] = $version_id;
			$row['revision_id'] = $revision_id;
			$row['del_flag'] = '0';

			$row['create_datetime'] = time();
			$row['create_user'] = $this->user_id;
			$row['update_datetime'] = time();
			$row['update_user'] = $this->user_id;

			$ret = $table_template->selectInsert($row);
			$new_contents_id = $table_template->selectMaxValue('contents_id');

			return $ret;
		}
	}
