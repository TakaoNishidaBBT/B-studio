<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_compare_pain extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->version_left = $this->global_session['version_left'];
			$this->version_right = $this->global_session['version_right'];
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if(isset($this->request['disp_mode'])) {
				$this->session['disp_mode'] = $this->request['disp_mode'];
			}
			if($this->request['node_id']) {
				if($this->request['target_id'] == 'left_tree') {
					$this->left_node_info = $this->getNodeInfo($this->version_left['version_id'], $this->request['node_id']);
					$path = $this->getPath($this->version_left['version_id'], $this->left_node_info);
					$this->right_node_info = $this->getNodeInfoFromPath($this->version_right['version_id'], $path . $this->left_node_info['node_name']);
				}
				else {
					$this->right_node_info = $this->getNodeInfo($this->version_right['version_id'], $this->request['node_id']);
					$path = $this->getPath($this->version_right['version_id'], $this->right_node_info);
					$this->left_node_info = $this->getNodeInfoFromPath($this->version_left['version_id'], $path . $this->right_node_info['node_name']);
				}

				$this->_select();
			}
		}

		function getNodeInfo($version_id, $node_id) {
			if($this->request['node_id'] == 'root') {
				$row['node_id'] = 'root';
			}
			else {
				$sql = "select *
						from " . B_DB_PREFIX . "resource_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "resource_node b
								," . B_DB_PREFIX . "version d
							where a.node_id=b.node_id
							and b.version_id = d.version_id
							and ((b.version_id < $version_id and b.revision_id < d.private_revision_id)
							or (b.version_id = $version_id and b.revision_id <= d.private_revision_id))
							group by node_id
						) and a.node_id = '$node_id'";

				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}
			return $row;
		}

		function getPath($version_id, $row) {
			if($row['parent_node'] && $row['parent_node'] != 'root') {
				$sql = "select *
						from " . B_DB_PREFIX . "resource_node a
						where concat(a.version_id, a.revision_id) = (
							select max(concat(b.version_id, b.revision_id))
							from " . B_DB_PREFIX . "resource_node b
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
			if(!$path) {
				$row['node_id'] = 'root';
				return $row;
			}
			$path_array = explode('/', $path);
			return $this->getResourceNode($version_id, $path_array);
		}

		function getResourceNode($version_id, $url, $node='', $level=0) {
			if(!count($url)) {
				return $node;
			}

			$sql = "select *
					from " . B_DB_PREFIX . "resource_node a
					where concat(a.version_id, a.revision_id) = (
						select max(concat(b.version_id, b.revision_id))
						from " . B_DB_PREFIX . "resource_node b
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
			else {
				$sql = str_replace('%NODE_NAME%', "", $sql);
			}

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if(!$row) {
				return;
			}

			return $this->getResourceNode($version_id, $url, $row, $level+1);
		}

		function _select() {
			$this->compare($left, $right);

			// start tramsaction
			$this->db->begin();

			// left pain
			if($this->left_node_info) {
				$version_id = $this->version_left['version_id'];
				$revision_id = $this->version_left['revision_id'];
				$sql = "update " . B_DB_PREFIX . "compare_version set compare_version_id='$version_id'";
				$this->db->query($sql);

				$left_node = new B_VNode($this->db
										, B_COMPARE_RESOURCE_NODE_VIEW
										, $version_id
										, $revision_id
										, $this->left_node_info['node_id']
										, null
										, 'all'
										, null);

				$left_node->compare($this->version_right['version_id'], $left, $right);
				$this->left_list[] = $left_node->getNodeList($this->session['open_nodes'], $node_id, $category, B_RESOURCE_DIR);
			}

			// right pain
			if($this->right_node_info) {
				$version_id = $this->version_right['version_id'];
				$revision_id = $this->version_right['revision_id'];
				$sql = "update " . B_DB_PREFIX . "compare_version set compare_version_id='$version_id'";
				$this->db->query($sql);

				$right_node = new B_VNode($this->db
										, B_COMPARE_RESOURCE_NODE_VIEW
										, $version_id
										, $version_id
										, $this->right_node_info['node_id']
										, null
										, 'all'
										, null);

				$right_node->compare($this->version_right['version_id'], $left, $right);
				$this->right_list[] = $right_node->getNodeList($this->session['open_nodes'], $node_id, $category, B_RESOURCE_DIR);
			}

			$this->db->commit();

			require_once('./config/compare_tree_config.php');
			$this->left_tree_config = $left_tree_config['script']['bframe_tree'];
			$this->right_tree_config = $left_right_config['script']['bframe_tree'];

			require_once('./config/compare_pain_config.php');
			if($this->session['disp_mode'] == 'detail') {
				$this->left = new B_DataGrid($this->db, $compare_pain_detail_config);
				$param['pos'] = 'left';
				$this->left->setCallBack($this, '_detail_callback', $param);

				$this->right = new B_DataGrid($this->db, $compare_pain_detail_config);
				$param['pos'] = 'right';
				$this->right->setCallBack($this, '_detail_callback', $param);

				$this->view_file = './view/view_compare_pain_detail.php';
			}
			else {
				$this->left = new B_DataGrid($this->db, $compare_pain_config);
				$param['pos'] = 'left';
				$this->left->setCallBack($this, '_thumb_callback', $param);

				$this->right = new B_DataGrid($this->db, $compare_pain_config);
				$param['pos'] = 'right';
				$this->right->setCallBack($this, '_thumb_callback', $param);

				$this->view_file = './view/view_compare_pain.php';
			}
			if(is_array($this->left_list[0]['children'])) {
				$this->left->bind($this->left_list[0]['children']);
			}
			if(is_array($this->right_list[0]['children'])) {
				$this->right->bind($this->right_list[0]['children']);
			}

			$this->disp_change = new B_Element($compare_pain_disp_change_config);
			$this->disp_change->setValue($this->session);
		}

		function compare(&$left, &$right) {
			$version_left = $this->version_left['version_id'];
			$version_right = $this->version_right['version_id'];

			$sql = "select a.*, c.cnt
					from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . " a
					,(select max(concat(version_id, revision_id)) version, node_id, count(*) cnt from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
						where node_id in(
							select node_id
							from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . " a
								," . B_DB_PREFIX . "version b
							where a.version_id = '$version_right'
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

			$sql = "select a.*, c.cnt
					from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . " a
					,(select max(concat(version_id, revision_id)) version, node_id, count(*) cnt from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
						where node_id in(
							select node_id from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
							where version_id = '$version_right'
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

		function _thumb_callback(&$array) {
			$row = &$array['row'];
			$pos = &$array['pos'];

			$path = $row->getElementByName('path');
			$contents_id = $row->getElementByName('contents_id');
			$update_datetime = $row->getElementByName('update_datetime');
			$node_status = $row->getElementByName('node_status');
			$node_class = $row->getElementByName('node_class');
			$img_border = $row->getElementByName('img_border');
			$icon = $row->getElementByName('icon');
			$file_info = $this->util->pathinfo($path->value);

			if($node_class->value == 'folder') {
				if($node_status->value == 'diff') {
					if($pos == 'left') {
						$icon->value = $icon->value_folder_diff_left;
					}
					else {
						$icon->value = $icon->value_folder_diff_right;
					}
				}
				else if($node_status->value == 'diff-child') {
					$icon->value = $icon->value_folder_diff_child;
				}
			}
			else {
				switch(strtolower($file_info['extension'])) {
					case 'jpg':
					case 'jpeg':
					case 'gif':
					case 'png':
						$src = B_RESOURCE_URL . B_THUMB_PREFIX . $contents_id->value . '.' . $file_info['extension'];
						$src.= '?' . $update_datetime->value;
						$icon->value = '<img src="' . $src . '" alt="thumbnail" />';
						if($node_status->value) {
							if($pos == 'left') {
								$img_border->start_html = $img_border->start_html_diff_left;
							}
							else {
								$img_border->start_html = $img_border->start_html_diff_right;
							}
						}
						break;

					default:
						$icon->value = $icon->value_file;
						if($node_status->value) {
							if($pos == 'left') {
								$icon->value = $icon->value_file_diff_left;
							}
							else {
								$icon->value = $icon->value_file_diff_right;
							}
						}
						break;
				}
			}
		}

		function _detail_callback(&$array) {
			$row = &$array['row'];
			$pos = &$array['pos'];

			$path = $row->getElementByName('path');
			$node_status = $row->getElementByName('node_status');
			$node_class = $row->getElementByName('node_class');
			$icon = $row->getElementByName('icon');

			if($node_class->value == 'folder') {
				if($node_status->value == 'diff') {
					if($pos == 'left') {
						$icon->value = $icon->value_folder_diff_left;
					}
					else {
						$icon->value = $icon->value_folder_diff_right;
					}
				}
				else if($node_status->value == 'diff-child') {
					$icon->value = $icon->value_folder_diff_child;
				}
			}
			else {
				if($node_status->value == 'diff') {
					if($pos == 'left') {
						$icon->value = $icon->value_file_diff_left;
					}
					else {
						$icon->value = $icon->value_file_diff_right;
					}
				}
				else {
					$icon->value = $icon->value_file;
				}
			}
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/resource_compare.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/resource_compare_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_compare_pain.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once($this->view_file);
		}
	}
