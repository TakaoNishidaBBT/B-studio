<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_VNode
	// -------------------------------------------------------------------------
	class B_VNode {
		function __construct($db, $view, $version, $revision, $node_id, $parent, $expand_level, $open_nodes, $node_count=false, $level=0, $row=null) {
			$this->db = $db;
			$this->view = $view;
			$this->version = $version;
			$this->revision = $revision;
			$this->node_id = $node_id;
			$this->status = true;
			$this->error_no = 0;
			$this->level = $level;
			$this->node_count = 0;

			if(!$node_id) return;

			if(!$row) {
				$row = $this->selectNode($node_id);
			}

			if($row) {
				$this->property = $row;
				foreach($this->property as $key => $value) {
					$this->$key = $value;
				}
			}
			else {
				$this->node_type = $node_id;
			}

			if($parent) {
				$this->parent = $parent;
			}
			else {
				$this->parent = $row['parent_node'];
			}

			if($this->node_class == 'leaf') return;

			if((is_array($open_nodes) && $open_nodes[$node_id]) || ($expand_level === 'all' || $level < $expand_level)) {
				$rs = $this->selectChild($node_id);
				while($row = $this->db->fetch_assoc($rs)) {
					$this->node_count++;
					if($row['node_type'] == 'folder') {
						$this->folder_count++;
					}
					$object = new B_VNode($db
										, $view
										, $version
										, $revision
										, $row['node_id']
										, $this
										, $expand_level
										, $open_nodes
										, $node_count
										, $level+1
										, $row);
					$this->addNodes($object);
				}
			}
			else {
				if($node_count) {
					$this->node_count = $this->getSubNodeCount($node_id);
				}
				else {
					$this->folder_count = $this->getSubFolderCount($node_id);
				}
			}
		}

		function addNodes($object) {
			$this->node[] = &$object;
		}

		function selectNode($node_id) {
			$sql = "select * from %VIEW% where node_id='$node_id'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function selectChild($node_id) {
			$sql = "select * from %VIEW% ";

			if($node_id) {
				$sql.= "where parent_node = '$node_id'";
			}
			else {
				// get root node
				$sql.= "where parent_node is null";
			}
			$sql.= " and del_flag='0'";
			$sql.= " order by disp_seq";

			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);

			return $rs;
		}

		function getSubFolderCount($node_id) {
			$node_id = $this->db->real_escape_string($node_id);
			$sql = "select count(*) cnt from %VIEW% ";

			if($node_id) {
				$sql.= "where parent_node = '$node_id'";
			}
			else {
				// get root node
				$sql.= "where parent_node is null";
			}
			$sql.= " and node_type = 'folder' and del_flag='0'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['cnt'];
		}

		function getSubNodeCount($node_id) {
			$node_id = $this->db->real_escape_string($node_id);
			$sql = "select count(*) cnt from %VIEW% ";

			if($node_id) {
				$sql.= "where parent_node = '$node_id'";
			}
			else {
				// get root node
				$sql.= "where parent_node is null";
			}
			$sql.= " and del_flag='0'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['cnt'];
		}

		function setConfig($config) {
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}

		function getHtml() {
			if(isset($this->start_html)) {
				$html.= $this->start_html;
			}

			if(isset($this->end_html)) {
				$html.= $this->end_html;
			}

			return $html;
		}

		function compare($version_right, $left, $right) {

			$this->setNodeStatus($version_right, $left, $right);
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->compare($version_right, $left, $right);
					if(!$this->node_diff_status && $ret) {
						$this->node_diff_status = 'diff_child';
					}
				}
			}
			return $this->node_diff_status;
		}

		function setNodeStatus($version_right, $left, $right) {
			if($this->version == $version_right) {
				if(!$right[$this->node_id]) return false;
				if($this->node_name != $left[$this->node_id]['node_name']) {
					$this->node_diff_status = 'diff';
					return true;
				}
				if($this->contents_id != $left[$this->node_id]['contents_id']) {
					$this->node_diff_status = 'diff';
					return true;
				}
				if($this->parent && $this->parent->node_id != $left[$this->node_id]['parent_node']) {
					$this->node_diff_status = 'diff';
					return true;
				}
			}
			else {
				if(!$left[$this->node_id]) return false;
				if($this->node_name != $right[$this->node_id]['node_name']) {
					$this->node_diff_status = 'diff';
					return true;
				}
				if($this->contents_id != $right[$this->node_id]['contents_id']) {
					$this->node_diff_status = 'diff';
					return true;
				}
				if($this->parent && $this->parent->node_id != $right[$this->node_id]['parent_node']) {
					$this->node_diff_status = 'diff';
					return true;
				}
			}
			if($right[$this->node_id]['updated_contents']) {
				$this->node_diff_status = 'diff';
				return true;
			}
			return false;
		}

		function getNodeList($open_nodes, $open_nodes_path, $node_id='', $category='', $dir='', $path='') {
			$list = $this->_getNodeList($open_nodes, $open_nodes_path, $node_id, $category, $dir, $path);

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$child_list[] = $this->node[$key]->getNodeList($open_nodes, $open_nodes_path, $node_id, $category, $dir, $list['path']);
				}
				$list['children'] = $child_list;
			}
			return $list;
		}

		function _getNodeList($open_nodes, $open_nodes_path, $node_id, $category, $dir, $path) {
			$list['node_id'] = $this->node_id;
			$list['node_type'] = $this->node_type;
			$list['node_class'] = $this->node_class;
			$list['node_name'] = mb_convert_encoding($this->node_name, 'UTF-8', B_MB_DETECT_ORDER);
			$list['contents_id'] = $this->contents_id;
			$list['node_diff_status'] = $this->node_diff_status;
			$list['node_count'] = $this->node_count;
			$list['folder_count'] = $this->folder_count;
			$list['create_datetime_u'] = $this->create_datetime;
			$list['create_datetime_t'] = date('Y/m/d H:i', $this->create_datetime);
			$list['update_datetime_u'] = $this->update_datetime;
			$list['update_datetime_t'] = date('Y/m/d H:i', $this->update_datetime);
			if($this->node_type != 'folder') {
				$list['file_size'] = $this->file_size;
				$list['human_file_size'] = B_Util::human_filesize($this->file_size, 'K');
			}
			if($this->image_size) {
				$list['image_size'] = $this->image_size;
				$list['human_image_size'] = $this->human_image_size;
			}

			$list['level'] = $this->level;
			if($this->node_id == $node_id) {
				$list[$category] = true;
			}
			if($path) {
				$path.= '/';
			}
			$list['path'] = $path . $list['node_name'];

			if($open_nodes[$this->node_id] || $open_nodes_path[$path]) {
				$list['opened'] = true;
			}
			return $list;
		}

		function getSelectNodeList() {
			$list = array();

			if($this->node_id != 'root') {
				for($i=1, $indent='' ; $i<$this->level ; $i++) $indent.= '&emsp;';	// indent
				$list[$this->node_id] = $indent . mb_convert_encoding($this->node_name, 'UTF-8', B_MB_DETECT_ORDER);
			}
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$list += $this->node[$key]->getSelectNodeList();
				}
			}
			return $list;
		}

		function getPath() {
			$sql_org = $this->sql . "and node_id = '%parent_node%'";

			$path = $this->node_name;

			for($node = $this->parent; $node && $node != 'root' && $node != 'trash'; $node = $row['parent_node']) {
				$sql = str_replace('%parent_node%', $node, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
				$path = $row['node_name'] . '/' . $path;
			}

			return $path;
		}

		function getFileSize($dir) {
			if($this->node_type == 'folder') return;
			$info = pathinfo($this->node_name);
			if(file_exists($dir . $this->contents_id . '.' . $info['extension'])) {
				return filesize($dir . $this->contents_id . '.' . $info['extension']);
			}
		}

		function getImageSize() {
			if($this->node_type == 'folder') return;

			$info = pathinfo($this->node_name);
			switch(strtolower($info['extension'])) {
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				$size = getimagesize(B_RESOURCE_DIR . $this->contents_id . '.' . $info['extension']);
				return $size[0] . 'x' . $size[1];
			}
		}

		function getNodeById($node_id) {
			if($this->node_id == $node_id) {
				return $this;
			}
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->getNodeById($node_id);
					if($ret) return $ret;
				}
			}
		}

		function getParentById($node_id) {
			for($id = $this->parent; $id && $id != $node_id; $id = $row['parent_node']) {
				$row = $this->selectNode($id);
			}
			return $id;
		}

		function callBack($call_back) {
			if(is_array($call_back)) {
				$obj = $call_back['obj'];
				$method = $call_back['method'];
				if(method_exists($obj, $method)) {
					$ret = $obj->$method($this);
				}
			}
			else {
				$ret = call_user_func_array($call_back, array($this));
			}

			return $ret;
		}

		function getErrorNo() {
			return $this->error_no;
		}
	}
