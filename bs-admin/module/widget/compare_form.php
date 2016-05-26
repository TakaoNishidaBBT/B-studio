<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class widget_compare_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];

			require_once('./config/compare_form_config.php');
			$this->form = new B_Element($form_config);

			$this->tab_control = new B_Element($tab_control_config);

			$this->contents_table = new B_Table($this->db, B_WIDGET_TABLE);

			$this->status = true;
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if($this->request['node_id']) {
				if($this->request['target_id'] == 'left_tree') {
					$this->left_node_info = $this->getNodeInfo($this->version_left['version_id'], $this->request['node_id']);
					if($this->left_node_info['node_type'] != 'widget' && $this->left_node_info['node_type'] != 'arias') {
						$this->setView('view_folder');
						return;
					}
					$path = $this->getPath($this->version_left['version_id'], $this->left_node_info);
					$this->right_node_info = $this->getNodeInfoFromPath($this->version_right['version_id'], $path . $this->left_node_info['node_name']);

					if(!$this->right_node_info) {
						$this->right_node_info = $this->getContentsNodeById($this->version_right['version_id'], $this->request['node_id']);
					}
				}
				else {
					$this->right_node_info = $this->getNodeInfo($this->version_right['version_id'], $this->request['node_id']);
					if($this->right_node_info['node_type'] != 'widget' && $this->right_node_info['node_type'] != 'arias') {
						$this->setView('view_folder');
						return;
					}
					$path = $this->getPath($this->version_right['version_id'], $this->right_node_info);
					$this->left_node_info = $this->getNodeInfoFromPath($this->version_left['version_id'], $path . $this->right_node_info['node_name']);

					if(!$this->left_node_info) {
						$this->left_node_info = $this->getContentsNodeById($this->version_left['version_id'], $this->request['node_id']);
					}
				}

				$this->_select();
			}
		}

		function getNodeInfo($version_id, $node_id) {
			$sql = "select *
					from " . B_DB_PREFIX . "widget_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "widget_node b
							," . B_DB_PREFIX . "version d
						where a.node_id=b.node_id
						and b.version_id = d.version_id
						and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
						or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
						group by node_id
					) and a.node_id = '$node_id'";

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			return $row;
		}

		function getPath($version_id, $row) {

			if($row['parent_node'] && $row['parent_node'] != 'root') {
				$sql = "select *
						from " . B_DB_PREFIX . "widget_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "widget_node b
								," . B_DB_PREFIX . "version d
							where a.node_id=b.node_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by node_id
						) and a.node_id = '%NODE_ID%'";

				$sql = str_replace('%NODE_ID%', $row['parent_node'], $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
				$path = $this->getPath($version_id, $row);
				if($row['node_type'] == 'folder') {
					$path.= $row['node_name'] . '/';
				}
			}
			return $path;
		}

		function getNodeInfoFromPath($version_id, $path) {
			$path_array = explode('/', $path);
			return $this->getContentsNode($version_id, $path_array);
		}

		function getContentsNode($version_id, $url, $node='', $level=0) {
			if(!count($url)) {
				return $node;
			}

			$sql = "select *
					from " . B_DB_PREFIX . "widget_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "widget_node b
							," . B_DB_PREFIX . "version d
						where a.node_id=b.node_id
						and b.version_id = d.version_id
						and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
						or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
						group by node_id
					) and a.parent_node='%PARENT_NODE%' %NODE_NAME%";

			if($node) {
				$sql = str_replace('%PARENT_NODE%', $node['node_id'], $sql);
			}
			else {
				$sql = str_replace('%PARENT_NODE%', 'root', $sql);
			}

			$node_name = array_shift($url);

			if($node_name) {
				$sql = str_replace('%NODE_NAME%', "and node_name='" . $node_name . "'", $sql);
			}
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if(!$row) {
				return;
			}

			return $this->getContentsNode($version_id, $url, $row, $level+1);
		}

		function getContentsNodeById($version_id, $node_id) {
			$sql = "select *
					from " . B_DB_PREFIX . "widget_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "widget_node b
							," . B_DB_PREFIX . "version d
						where a.node_id=b.node_id
						and b.version_id = d.version_id
						and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
						or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
						group by node_id
					) and a.node_id='$node_id'";

			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function _select() {
			if($this->left_node_info['contents_id']) {
				$version_id = $this->version_left['version_id'];
				$contents_id = $this->left_node_info['contents_id'];
				$sql = "select *
						from " . B_DB_PREFIX . "widget a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "widget b
								," . B_DB_PREFIX . "version d
							where a.contents_id=b.contents_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.contents_id
						) and a.contents_id = '$contents_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);

				$param['html_left'] = $row['html'];
				$param['css_left'] = $row['css'];
				$param['php_left'] = $row['php'];

				// template
				$node_id = $row['template_id'];

				$sql = "select *
						from " . B_DB_PREFIX . "template_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "template_node b
								," . B_DB_PREFIX . "version d
							where a.node_id=b.node_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.node_id
						) and a.node_id = '$node_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);

				$param['template_name_left'] = $row['node_name'];

			}

			if($this->right_node_info['contents_id']) {
				$version_id = $this->version_right['version_id'];
				$contents_id = $this->right_node_info['contents_id'];
				$sql = "select *
						from " . B_DB_PREFIX . "widget a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "widget b
								," . B_DB_PREFIX . "version d
							where a.contents_id=b.contents_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.contents_id
						) and a.contents_id = '$contents_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);

				$param['html_right'] = $row['html'];
				$param['css_right'] = $row['css'];
				$param['php_right'] = $row['php'];

				// template
				$node_id = $row['template_id'];

				$sql = "select *
						from " . B_DB_PREFIX . "template_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "template_node b
								," . B_DB_PREFIX . "version d
							where a.node_id=b.node_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.node_id
						) and a.node_id = '$node_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);

				$param['template_name_right'] = $row['node_name'];
			}

			$this->form->setValue($param);

			$this->form->validate();
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/widget_compare_form.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/diff2html/diff2html.css" type="text/css" rel="stylesheet" media="all" />');

			$this->html_header->appendProperty('script', '<script src="js/bframe_compare.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_preview.js" type="text/javascript"></script>');

			$this->html_header->appendProperty('script', '<script src="js/jquery/jquery.min.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/diff2html/highlight.min.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/diff2html/scala.min.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/diff2html/difflib.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/diff2html/diff2html.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/diff2html/diff2html-ui.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_compare_form.php');
		}

		function view_folder() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/widget_form.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			echo '<body></body>';
		}
	}
