<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_select_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			if(array_key_exists('template_id', $this->request)) {
				$this->openCurrenNode($this->request['template_id']);
				$this->session['current_node'] = $this->request['template_id'];
			}
			require_once('./config/select_tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, null
									, null
									, 1
									, null);

			$this->tree->setConfig($this->tree_config);
		}

		function openCurrenNode($node_id) {
			$sql_org = "select parent_node from %VIEW% where node_id='%NODE_ID%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_TEMPLATE_NODE_VIEW, $sql_org);

			for($id = $node_id; $id && $id != 'root'; $id = $row['parent_node']) {
				$this->session['open_nodes'][$id] = true;

				$sql = str_replace('%NODE_ID%', $id, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_select_tree.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function getNodeList() {
			$response['status'] = true;
			$response['current_node'] = $this->session['current_node'];
			$this->session['open_nodes'][$this->request['node_id']] = true;

			$root_node = new B_Node($this->db
									, B_TEMPLATE_NODE_TABLE
									, B_WORKING_TEMPLATE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']
									, true);

			$list[] = $root_node->getNodeList($this->request['node_id'], 'select');

			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
			exit;
		}

		function closeNode() {
			$this->session['open_nodes'][$this->request['node_id']] = false;

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo json_encode($response);
			exit;
		}
	}
