<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_compare_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];

			require_once('./config/compare_form_config.php');
			$this->form = new B_Element($form_config);
			$this->config_form = new B_Element($config_form_config);

			$obj = $this->form->getElementByName('config_form');
			$obj->addElement($this->config_form);

			$this->tab_control = new B_Element($tab_control_config);

			$this->template_table = new B_Table($this->db, B_CONTENTS_TABLE);

			$this->status = true;
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if($this->request['node_id']) {
				if($this->request['target_id'] == 'left_tree') {
					$this->left_node_info = $this->getNodeInfo($this->version_left['version_id'], $this->request['node_id']);
					if($this->left_node_info['node_type'] != 'template' && $this->left_node_info['node_type'] != 'arias') {
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
					if($this->right_node_info['node_type'] != 'template' && $this->right_node_info['node_type'] != 'arias') {
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
					from " . B_DB_PREFIX . "template_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "template_node b
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
						from " . B_DB_PREFIX . "template_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "template_node b
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
				if($row['node_type'] == 'template') {
					$path.= $row['node_name'] . '/';
				}
			}
			return $path;
		}

		function getNodeInfoFromPath($version_id, $path) {
			$path_array = explode('/', $path);
			$node =  $this->getContentsNode($version_id, $path_array);
			return $node;
		}

		function getContentsNode($version_id, $url, $node='', $level=0) {
			if(!count($url)) {
				return $node;
			}

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
					from " . B_DB_PREFIX . "template_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "template_node b
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
						from " . B_DB_PREFIX . "template a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "template b
								," . B_DB_PREFIX . "version d
							where a.contents_id=b.contents_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.contents_id
						) and a.contents_id = '$contents_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);
				$param['start_html_left'] = $row['start_html'];
				$param['end_html_left'] = $row['end_html'];
				$param['css_left'] = $row['css'];
				$param['php_left'] = $row['php'];
				$param['title_left'] = $row['title'];
				$param['bread_crumb_name_left'] = $row['bread_crumb_name'];
				$param['keyword_left'] = $row['keyword'];
				$param['description_left'] = $row['description'];
				$param['external_css_left'] = $row['external_css'];
				$param['external_js_left'] = $row['external_js'];
				$param['header_element_left'] = $row['header_element'];
			}

			if($this->right_node_info['contents_id']) {
				$version_id = $this->version_right['version_id'];
				$contents_id = $this->right_node_info['contents_id'];
				$sql = "select *
						from " . B_DB_PREFIX . "template a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "template b
								," . B_DB_PREFIX . "version d
							where a.contents_id=b.contents_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by b.template_id
						) and a.contents_id = '$contents_id'";

				$rs=$this->db->query($sql);
				$row=$this->db->fetch_assoc($rs);

				$param['start_html_right'] = $row['start_html'];
				$param['end_html_right'] = $row['end_html'];
				$param['css_right'] = $row['css'];
				$param['php_right'] = $row['php'];
				$param['title_right'] = $row['title'];
				$param['bread_crumb_name_right'] = $row['bread_crumb_name'];
				$param['template_name_right'] = $row['template_name'];
				$param['keyword_right'] = $row['keyword'];
				$param['description_right'] = $row['description'];
				$param['external_css_right'] = $row['external_css'];
				$param['external_js_right'] = $row['external_js'];
				$param['header_element_right'] = $row['header_element'];
			}

			$this->form->setValue($param);
			$this->form->validate();
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template_form.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/mergely/codemirror.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/mergely/mergely.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/jquery/jquery.min.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/mergely/codemirror.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/mergely/mergely.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_compare.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_preview.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_compare_form.php');
		}

		function view_folder() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template_form.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			echo '<body></body>';
		}
	}
