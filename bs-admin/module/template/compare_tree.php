<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_compare_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];
			$this->tree_left = new B_VNode($this->db
									, B_COMPARE_TEMPLATE_NODE_VIEW
									, $this->version_left['version_id']
									, $this->version_left['revision_id']
									, null
									, null
									, 0
									, null);

			$this->tree_right = new B_VNode($this->db
									, B_COMPARE_TEMPLATE_NODE_VIEW
									, $this->version_right['version_id']
									, $this->version_right['revision_id']
									, null
									, null
									, 0
									, null);

			require_once('./config/compare_tree_config.php');
			$this->tree_left->setConfig($left_tree_config);
			$this->tree_right->setConfig($right_tree_config);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/template_compare_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_compare_tree.js" type="text/javascript"></script>');

			$this->showHtmlHeader();

			require_once('./view/view_compare_tree.php');
		}

		function getNodeList() {
			if(!isset($this->session['open_nodes'])) {
				$this->session['open_nodes']['root'] = true;
			}
			$this->response('root', 'select', $this->request['target_id']);

			exit;
		}

		function openNode() {
			if($this->request['node_id']) {
				$this->session['open_nodes'][$this->request['node_id']] = true;
			}
			if($this->request['path']) {
				$this->session['open_nodes_path'][$this->request['path']] = true;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			$param['status'] = true;
			echo json_encode($param);
			exit;
		}

		function closeNode() {
			if($this->request['node_id']) {
				$this->session['open_nodes'][$this->request['node_id']] = false;
			}
			if($this->request['path']) {
				$this->session['open_nodes_path'][$this->request['path']] = false;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			$param['status'] = true;
			echo json_encode($param);
			exit;
		}

		function response($node_id, $category, $target_id) {
			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}
			if($target_id == 'left_tree') {
				$version = $this->version_left;
			}
			else {
				$version = $this->version_right;
			}

			// start tramsaction
			$this->db->begin();
			$sql = "update " . B_DB_PREFIX . "compare_version set compare_version_id='" . $version['version_id'] . "'";
			$this->db->query($sql);

			$root_node = new B_VNode($this->db
									, B_COMPARE_TEMPLATE_NODE_VIEW
									, $version['version_id']
									, $version['revision_id']
									, 'root'
									, null
									, 'all'
									, $this->session['open_nodes']
									, true);

			$this->compare($left, $right);
			$root_node->compare($this->version_right['version_id'], $left, $right);

			$list[] = $root_node->getNodeList($this->session['open_nodes'], $this->session['open_nodes_path'], $node_id, $category);

			$this->db->commit();

			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
		}

		function compare(&$left, &$right) {
			$version_left = $this->version_left['version_id'];
			$version_right = $this->version_right['version_id'];

			$sql = "select a.*, a.contents_id updated_contents, c.cnt
					from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . " a
					,(select max(concat(version_id, revision_id)) version, node_id, count(*) cnt from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . "
						where node_id in(
							select node_id
							from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . " a
								," . B_DB_PREFIX . "version b
							where contents_id in (
								select a.contents_id
								from " . B_DB_PREFIX . B_TEMPLATE_TABLE . " a
								," . B_DB_PREFIX . "version b
								where a.version_id = '$version_right'
								or (a.version_id = $version_left and a.version_id = b.version_id and a.revision_id = b.private_revision_id)
							)
							or a.version_id = '$version_right'
							or (a.version_id = $version_left and a.version_id = b.version_id and a.revision_id = b.private_revision_id)
							group by node_id
						)
						and version_id < '$version_right'
						group by node_id
					) c
					where concat(a.version_id, a.revision_id) = c.version
					and a.node_id = c.node_id";

			$rs = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($rs)) {
				$left[$row['node_id']] = $row;
			}

			$sql = "select a.*, a.contents_id updated_contents, c.cnt
					from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . " a
					,(select max(concat(version_id, revision_id)) version, node_id, count(*) cnt from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . "
						where node_id in(
							select node_id
							from " . B_DB_PREFIX . B_TEMPLATE_NODE_TABLE . " a
							where contents_id in (
								select a.contents_id
								from " . B_DB_PREFIX . B_TEMPLATE_TABLE . " a
								where a.version_id = '$version_right'
							)
							or a.version_id = '$version_right'
							group by node_id
						)
						and version_id <= '$version_right'
						group by node_id
					) c
					where concat(a.version_id, a.revision_id) = c.version
					and a.node_id = c.node_id";

			$rs = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($rs)) {
				$right[$row['node_id']] = $row;
			}
		}
	}
