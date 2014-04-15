<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class category2_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			if(array_key_exists('category_id', $this->request)) {
				$this->openCurrenNode($this->request['category_id']);
				$this->session['current_node'] = $this->request['category_id'];
			}
			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_CATEGORY2_TABLE
									, B_CATEGORY2_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, null
									, null
									, null
									, 0
									, null);

			$this->tree->setConfig($this->tree_config);
		}

		function openCurrenNode($node_id) {
			$sql_org = "select parent_node from %VIEW% where node_id='%NODE_ID%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . B_CATEGORY2_TABLE, $sql_org);

			for($id = $node_id; $id && $id != 'root'; $id = $row['parent_node']) {
				$this->session['open_nodes'][$id] = true;

				$sql = str_replace('%NODE_ID%', $id, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/category.css" type="text/css" rel="stylesheet" media="all">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');

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
											, B_CATEGORY2_TABLE
											, B_CATEGORY2_VIEW
											, $this->version['working_version_id']
											, $this->version['revision_id']
											, $this->request['source_node_id'][0]
											, null
											, 'all'
											, null);

					// start transaction
					$this->db->begin();

					switch($this->request['mode']) {
					case 'arias':
						$ret = $source_node->arias($this->request['destination_node_id'], $this->user_id);
						break;

					case 'copy':
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
							, B_CATEGORY2_TABLE
							, B_CATEGORY2_VIEW
							, $this->version['working_version_id']
							, $this->version['revision_id']
							, $this->request['destination_node_id']
							, null
							, 0
							, null);

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
				$node = new B_Node($this->db
									, B_CATEGORY2_TABLE
									, B_CATEGORY2_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['delete_node_id'][0]
									, null
									, 0
									, null);

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
				else if($this->tree->checkDuplicateByName($this->request['node_id'], $this->request['node_name'])) {
					$this->message = '名前を変更できません。指定されたカテゴリ名は既に存在します。別の名前を指定してください。';
					$status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_CATEGORY2_TABLE
									, B_CATEGORY2_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['node_id']
									, null
									, 0
									, null);

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
									, B_CATEGORY2_TABLE
									, B_CATEGORY2_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['parent_node_id']
									, null
									, 0
									, null);

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
			$response['current_node'] = $this->session['current_node'];
			if($this->message) {
				$response['message'] = $this->message;
			}
			$root_node = new B_Node($this->db
									, B_CATEGORY2_TABLE
									, B_CATEGORY2_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']);

			$list[] = $root_node->getNodeList($node_id, $category);

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
	}
