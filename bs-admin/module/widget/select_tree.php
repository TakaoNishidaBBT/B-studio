<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class widget_select_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			if(array_key_exists('opener', $this->request)) {
				$this->session['opener'] = $this->request['opener'];
			}
			$this->opener = $this->session['opener'];

			require_once('./config/select_tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_WIDGET_NODE_TABLE
									, B_WORKING_WIDGET_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'node'
									, null
									, null
									, 1
									, null);

			$this->tree->setConfig($this->tree_config);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/widget_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_select_tree.php');
		}

		function getNodeList() {
			$response['status'] = true;
			$this->session['open_nodes'][$this->request['node_id']] = true;

			$root_node = new B_Node($this->db
									, B_WIDGET_NODE_TABLE
									, B_WORKING_WIDGET_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']);

			$list[] = $root_node->getNodeList($this->request['node_id'], 'select');

			if($list) {
				$response['node_info'] = $list;
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
			exit;

		}

		function closeNode() {
			$this->session['open_nodes'][$this->request['node_id']] = false;

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo $this->util->json_encode($response);
			exit;
		}
	}
