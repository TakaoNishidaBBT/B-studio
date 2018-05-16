<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Node
	// 
	// Error No: 0	DB error
	// 		   : 1	paste(or move) node to sub folder
	// 		   : 2	node count is not much
	// 		   : 3	node was updated by another user
	// -------------------------------------------------------------------------
	class B_Node {
		function __construct($db, $table, $view, $version, $revision, $node_id, $parent, $expand_level, $open_nodes, $node_count=false, $sort_mode='manual', $level=0, $row=null) {
			$this->db = $db;
			$this->table = $table;
			$this->view = $view;
			$this->version = $version;
			$this->revision = $revision;
			$this->node_id = $node_id;
			$this->status = true;
			$this->error_no = 0;
			$this->sort_mode = $sort_mode;
			$this->level = $level;
			$this->node_count = 0;

			if(!$node_id) return;

			$this->tbl_node = new B_Table($this->db, $this->table);
			$this->view_node = new B_Table($this->db, $this->view);
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
					$object = new B_Node($db
										, $this->table
										, $view
										, $version
										, $revision
										, $row['node_id']
										, $this
										, $expand_level
										, $open_nodes
										, $node_count
										, $sort_mode
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

		function addNodes(&$object) {
			$this->node[] = $object;
		}

		function isExists($node_name) {
			if(!is_array($this->node)) return false;

			foreach($this->node as $node) {
				if($node->node_name == $node_name) {
					return $node->node_id;
				}
			}
			return false;
		}

		function selectNode($node_id) {
			$sql = "select * from %VIEW% where node_id='$node_id'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function selectChild($node_id) {
			$node_id = $this->db->real_escape_string($node_id);
			$sql = "select * from %VIEW% ";

			if($node_id) {
				$sql.= "where parent_node = '$node_id'";
			}
			else {
				// get root node
				$sql.= "where parent_node is null";
			}
			$sql.= " and del_flag='0'";
			if($this->sort_mode == 'manual') {
				$sql.= " order by disp_seq";
			}
			else {
				$sql.= " order by binary node_name";
			}
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);

			return $rs;
		}

		function getChildNode($node_id) {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					if($node_id == $this->node[$key]->node_id) {
						return $this->node[$key];
					}
				}
			}
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

		function getNodeTypeById($node_id) {
			$node_id = $this->db->real_escape_string($node_id);
			$sql = "select * from %VIEW% where node_id='$node_id'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			return $row['node_type'];
		}

		function sort(&$list) {
			if(!is_array($list)) return;

			uasort($list, array($this,'_sort_callback'));

			$i=0;
			foreach($list as &$value) {
				$value['order'] = $i++;
			}
			ksort($list);
		}

		function _sort_callback($a, $b) {
			$key = $this->sort_key ? $this->sort_key : 'node_name';
			$order = $this->sort_order ? $this->sort_order : 'asc';

			if($a['node_type'] == $b['node_type']) {
				if($a[$key] == $b[$key]) $key = 'node_name';
				if($order == 'asc') {
					$ret = ($a[$key] < $b[$key]) ? -1 : 1;
				}
				else {
					$ret = ($a[$key] >= $b[$key]) ? -1 : 1;
				}
			}
			else {
				if($order == 'asc') {
					$ret = ($a['node_type'] > $b['node_type']) ? -1 : 1;
				}
				else {
					$ret = ($a['node_type'] < $b['node_type']) ? -1 : 1;
				}
			}
			return $ret;
		}

		function setSortKey($sort_key) {
			$this->sort_key = $sort_key;
		}

		function setSortOrder($sort_order) {
			$this->sort_order = $sort_order;
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

		function getNodeList($node_id='', $category='', $dir='', $path='', $node_status='') {
			$list = $this->_getNodeList($node_id, $category, $dir, $path, $node_status);

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$child_list[] = $this->node[$key]->getNodeList($node_id, $category, $dir, $list['path'], $node_status);
				}
				if($this->sort_key) {
					$this->sort($child_list);
				}
				$list['children'] = $child_list;
			}
			return $list;
		}

		function _getNodeList($node_id, $category, $dir, $path, &$node_status) {
			if($this->property) {
				foreach($this->property as $key => $value) {
					$list[$key] = $this->$key;
				}
			}
			else {
				$list['node_id'] = $this->node_id;
				$list['node_type'] = $this->node_type;
				$list['node_class'] = $this->node_class;
				$list['node_name'] = $this->node_name;
			}

			$list['node_count'] = $this->node_count;
			$list['folder_count'] = $this->folder_count;
			$list['create_datetime_u'] = $this->create_datetime;
			$list['create_datetime_t'] = date('Y/m/d H:i', $this->create_datetime);
			$list['update_datetime_u'] = $this->update_datetime;
			$list['update_datetime_t'] = date('Y/m/d H:i', $this->update_datetime);

			// image size
			if($this->node_type != 'folder') {
				$list['file_size'] = $this->file_size;
				$list['human_file_size'] = B_Util::human_filesize($this->file_size, 'K');
			}
			if($this->image_size) {
				$list['image_size'] = $this->image_size;
				$list['human_image_size'] = $this->human_image_size;
			}

			// node status
			$node_status = $node_status ? $node_status : $this->node_status;
			if($node_status) {
				$list['node_status'] = $node_status;
			}

			$list['level'] = $this->level;
			$list['disp_seq'] = $this->disp_seq;
			if($this->node_id == $node_id) {
				$list[$category] = true;
			}
			if($path && substr($path, -1) != '/') {
				$path.= '/';
			}
			$list['path'] = $path . $list['node_name'];

			return $list;
		}

		function getSelectNodeList() {
			$list = array();

			if($this->node_id != 'root') {
				for($i=1, $indent=''; $i<$this->level; $i++) $indent.= '&emsp;';	// indent
				$key = $this->node_id . ',' . $this->path; 
				$list[$key] = $indent . mb_convert_encoding($this->node_name, 'UTF-8', B_MB_DETECT_ORDER);
			}
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$list += $this->node[$key]->getSelectNodeList();
				}
			}
			return $list;
		}

		function getPath() {
			$parent_path = $this->getParentPath();
			return $parent_path . $this->node_name;
		}

		function getParentPath() {
			$sql_org = "select * from %VIEW% where node_id = '%parent_node%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql_org);

			for($node = $this->parent; $node && $node != 'root' && $node != 'trash'; $node = $row['parent_node']) {
				$sql = str_replace('%parent_node%', $node, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
				$path = $row['node_name'] . '/' . $path;
			}

			return $path;
		}

		function arias($destination_node_id, $user_id, &$new_node_id, $callback=null) {
			if(!$this->node_id) return;

			// if destination node is my child
			if($this->isMyChild($destination_node_id)) {
				$this->error_no = 1;
				return false;
			}

			$arias_node_name = $this->getNewNodeName($destination_node_id, $this->node_name, 'arias');

			if($callback) {
				if(!$ret = $this->callBack($callback)) return $ret;
			}

			if($this->property) {
				foreach($this->property as $key => $value) {
					$param[$key] = $this->$key;
				}
			}

			// get destination node record
			$destination_node = $this->selectNode($destination_node_id);

			if($destination_node_id == 'root') {
				$param['path'] = '/';
			}
			else {
				$param['path'] = $destination_node['path'] . $destination_node['node_id'] . '/';
			}

			$param['node_id'] = '';
			$param['parent_node'] = $destination_node_id;
			$param['disp_seq'] = $this->getMaxDispSeq($destination_node_id, $destination_node['disp_seq']);
			$param['node_type'] = 'arias';
			$param['node_class'] = $this->node_class;
			$param['node_name'] = $arias_node_name;
			$param['contents_id'] = $this->contents_id;
			$param['del_flag'] = '0';
			$param['create_datetime'] = time();
			$param['create_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['update_user'] = $user_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			$ret = $this->tbl_node->selectInsert($param);
			if(!$ret) return $ret;

			$node_id = $this->tbl_node->selectMaxValue('node_id');
			$new_node_id[] = $node_id;

			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->arias($node_id, $user_id);
					if(!$ret) return $ret;
				}
			}

			return true;
		}

		function copy($destination_node_id, $user_id, &$new_node_id, $callback=null) {
			if(!$this->node_id) return;

			// if destination node is my child
			if($this->isMyChild($destination_node_id)) {
				$this->error_no = 1;
				return false;
			}

			$copy_node_name = $this->getNewNodeName($destination_node_id, $this->node_name, 'copy');

			if($callback) {
				if(!$ret = $this->callBack($callback)) return $ret;
			}

			if($this->property) {
				foreach($this->property as $key => $value) {
					$param[$key] = $this->$key;
				}
			}

			// get destination node record
			$destination_node = $this->selectNode($destination_node_id);

			if($destination_node_id == 'root') {
				$param['path'] = '/';
			}
			else {
				$param['path'] = $destination_node['path'] . $destination_node['node_id'] . '/';
			}

			$param['node_id'] = '';
			$param['parent_node'] = $destination_node_id;
			$param['disp_seq'] = $this->getMaxDispSeq($destination_node_id, $destination_node['disp_seq']);
			$param['node_type'] = $this->node_type;
			$param['node_class'] = $this->node_class;
			$param['node_name'] = $copy_node_name;
			$param['contents_id'] = $this->new_contents_id;
			$param['del_flag'] = '0';
			$param['create_datetime'] = time();
			$param['create_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['update_user'] = $user_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			$ret = $this->tbl_node->selectInsert($param);

			if(!$ret) return $ret;

			$node_id = $this->tbl_node->selectMaxValue('node_id');
			$new_node_id[] = $node_id;

			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->copy($node_id, $user_id, $new_node_id, $callback);
					if(!$ret) return $ret;
				}
			}

			return true;
		}

		function cloneNode($node_id) {
			$param['version_id'] = $this->version;
			$param['node_id'] = $node_id;
			$param['revision_id'] = $this->revision;

			$row = $this->tbl_node->selectByPk($param);
			if(!$row) {
				$node_id = $this->db->real_escape_string($node_id);
				$sql = "select * from %VIEW% where node_id='$node_id'";
				$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
				$rs = $this->db->query($sql);
				$param = $this->db->fetch_assoc($rs);
				if($param) {
					$param['version_id'] = $this->version;
					$param['revision_id'] = $this->revision;

					$this->tbl_node->insert($param);
				}
			}
		}

		function move($destination_node_id, $user_id, &$disp_seq=0) {
			if(!$this->node_id) return;

			// if destination node is my child
			if($this->isMyChild($destination_node_id)) {
				$this->error_no = 1;
				return false;
			}

			if($call_back) {
				if(!$ret = $this->callBack($call_back)) return $ret;
			}

			$this->cloneNode($this->node_id);

			// get destination node record
			$destination_node = $this->selectNode($destination_node_id);

			switch($destination_node_id) {
			case 'root':
				$param['path'] = '/';
				break;

			case 'trash':
				$param['path'] = '/trash/';
				break;
			
			default:
				$param['path'] = $destination_node['path'] . $destination_node['node_id'] . '/';
				break;
			}

			$param['parent_node'] = $destination_node_id;
			$param['node_id'] = $this->node_id;
			if(!$disp_seq) $this->disp_seq = $this->getMaxDispSeq($destination_node_id, $destination_node['disp_seq']);
			$param['disp_seq'] = $this->disp_seq;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			$this->tbl_node->update($param);

			// update path of related records
			$this->updatePath($destination_node_id, $destination_node);

			for($i=0; $i < count($this->node); $i++) {
				$this->node[$i]->_updateDispSeq($user_id, str_pad($i+1, 4, '0', STR_PAD_LEFT));
			}

			return true;
		}

		function updatePath($destination_node_id, $destination_node) {
			switch($destination_node_id) {
			case 'root':
				$to = '/' . $this->node_id . '/'; 	
				break;

			case 'trash':
				$to = '/trash/' . $this->node_id . '/'; 	
				break;
			
			default:
				$to = $destination_node['path'] . $destination_node['node_id'] . '/' . $this->node_id . '/';
				break;
			}
			$from = $this->path . $this->node_id . '/';
			$path = $this->path . $this->node_id . '/%';

			if($this->tbl_node->isColumnExist('version_id')) {
				$sql = "select * from %TABLE% where path like '$path'";
				$sql = str_replace('%TABLE%', B_DB_PREFIX . $this->table, $sql);
				$rs = $this->db->query($sql);
				while($row = $this->db->fetch_assoc($rs)) {
					$this->cloneNode($row['node_id']);
				}

				$sql = "update %TABLE% set path = replace(path, '$from', '$to')
						where version_id='%VERSION_ID%' and revision_id='%REVISION_ID%' and path like '$path'";

				$sql = str_replace('%TABLE%', B_DB_PREFIX . $this->table, $sql);
				$sql = str_replace('%VERSION_ID%', $this->version, $sql);
				$sql = str_replace('%REVISION_ID%', $this->revision, $sql);
			}
			else {
				$sql = "update %TABLE% set path = replace(path, '$from', '$to')
						where path like '$path'";

				$sql = str_replace('%TABLE%', B_DB_PREFIX . $this->table, $sql);
				$sql = str_replace('%VERSION_ID%', $this->version, $sql);
				$sql = str_replace('%REVISION_ID%', $this->revision, $sql);
			}

			$rs = $this->db->query($sql);
		}

		function delete($callback=null) {
			if(!$this->node_id) return;

			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->delete($callback);
					if(!$ret) return $ret;
				}
			}
			$this->cloneNode($this->node_id);

			if($callback) {
				if(!$ret = $this->callBack($callback)) return $ret;
			}

			$param['node_id'] = $this->node_id;
			$param['del_flag'] = '1';
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->update($param);
		}

		function physicalDelete($callback=null) {
			if(!$this->node_id) return;

			if($callback) {
				if(!$ret = $this->callBack($callback)) return $ret;
			}

			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->physicalDelete($callback);
					if(!$ret) return $ret;
				}
			}

			$param['node_id'] = $this->node_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->deleteByPk($param);
		}

		function insert($node_type, $node_class, $user_id, &$new_node_id, &$new_node_name) {
			if(!$this->node_id) return;

			$default_node_name = $this->script['bframe_tree']['icon'];
			$new_node_name = $this->getNewNodeName($this->node_id, $default_node_name[$node_type]['new'], 'insert');
			$param['parent_node'] = $this->node_id;
			if($this->node_id == 'root') {
				$param['path'] = '/';
			}
			else {
				$param['path'] = $this->path . $this->node_id . '/';
			}
			$param['node_type'] = $node_type;
			$param['node_class'] = $node_class;
			$param['node_name'] = $new_node_name;
			$param['disp_seq'] = $this->getMaxDispSeq($this->node_id, $this->disp_seq);
			$param['del_flag'] = '0';
			$param['create_datetime'] = time();
			$param['create_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['update_user'] = $user_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			$ret = $this->tbl_node->selectInsert($param);
			$new_node_id = $this->tbl_node->selectMaxValue('node_id');

			return $ret;
		}

		function getNewNodeName($parent_node, $default_name, $mode) {
			$parent_node = $this->db->real_escape_string($parent_node);
			$sql = "select node_name from %VIEW% where parent_node='$parent_node' order by node_name";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);

			for($cnt=0; $row[$cnt] = $this->db->fetch_assoc($rs); $cnt++);

			if(strlen($default_name) == mb_strlen($default_name)) {
				$info = pathinfo($default_name);
			}
			else {
				$info['filename'] = $default_name;
				$info['extension'] = '';
				$info['dir_name'] = '';
			}

			for($i=2, $node_name = $info['filename'] ;; $node_name = $prefix . $info['filename'] . $extend) {
				if($info['extension']) {
					for($j=0; $j<$cnt && $row[$j]['node_name'] != $node_name . '.' . $info['extension']; $j++);
				}
				else {
					for($j=0; $j<$cnt && $row[$j]['node_name'] != $node_name; $j++);
				}

				if($j == $cnt) {
					break;
				}
				switch($mode) {
				case 'insert':
					$extend = '(' . $i++ . ')';
					break;

				case 'copy':
					if($prefix) {
						$extend = '(' . $i++ . ')';
					}
					else {
						$prefix.= 'copy_of_';
					}
					break;

				case 'arias':
					$prefix.= 'arias_of_';
					break;
				}
			}
			if($info['extension']) {
				return $node_name . '.' . $info['extension'];
			}
			else {
				return $node_name;
			}
		}

		function setContentsId($contents_id, $user_id) {
			if(!$this->node_id) return;

			$this->cloneNode($this->node_id);

			$param['node_id'] = $this->node_id;
			$param['contents_id'] = $contents_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->update($param);
		}

		function updateDispSeq($request, $user_id) {
			if(!$this->node_id) return;

			for($i=0; $i<count($request['node_list']); $i++) {
				if($this->isMyParent($request['node_list'])) {
					$this->error_no = 1;
					return false;
				}
			}
			if($this->node_count != count($request['node_list']) &&
				$this->node_count+1 != count($request['node_list'])) {
				$this->error_no = 2;
				return false;
			}

			for($i=0; $i<count($request['node_list']); $i++) {
				if(is_array($this->node)) {
					foreach(array_keys($this->node) as $key) {
						if($this->node[$key]->node_id == $request['node_list'][$i]) {
							if($this->node[$key]->update_datetime != $request['update_datetime'][$i]) {
								$this->error_no = 3;
								return false;
							}
						}
					}
				}
			}

			// update
			for($i=0; $i<count($request['node_list']); $i++) {
				$child = $this->getChildNode($request['node_list'][$i]);
				if($child) {
					$child->_updateDispSeq($user_id, str_pad($i+1, 4, '0', STR_PAD_LEFT));
				}
			}
			return true;
		}

		function _updateDispSeq($user_id, $disp_seq=0) {
			$this->cloneNode($this->node_id);

			$param['node_id'] = $this->node_id;
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();

			if($disp_seq) {
				$this->disp_seq = $this->parent->disp_seq . '/' . $disp_seq;
			}
			else {
				$this->disp_seq = $this->parent->disp_seq . '/' . substr($this->disp_seq, -4);
			}
			$param['disp_seq'] = $this->disp_seq;

			$this->tbl_node->update($param);

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->_updateDispSeq($user_id);
				}
			}
		}

		function saveName($node_name, $user_id, $option=null) {
			if(!$this->node_id) return;

			$this->cloneNode($this->node_id);

			$param['node_id'] = $this->node_id;
			$param['node_name'] = $node_name;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			if(is_array($option)) {
				foreach($option as $key => $value) {
					$param[$key] = $value;
				}
			}

			return $this->tbl_node->update($param);
		}

		function updateNode($param, $user_id) {
			if(!$this->node_id) return;

			$this->cloneNode($this->node_id);

			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;
			$param['node_id'] = $this->node_id;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();

			return $this->tbl_node->update($param);
		}

		function getMaxDispSeq($parent_node_id, $parent_disp_seq) {
			$sql = "select lpad(cast((ifnull(max(right(disp_seq, 4)), 0) +1) as  char), 4, '0') disp_seq from %VIEW% where parent_node='%PARENT_NODE%'";

			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$sql = str_replace('%PARENT_NODE%', $this->db->real_escape_string($parent_node_id), $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if($parent_node_id == 'trash') {
				return '/trash/' . $row['disp_seq'];
			}
			return $parent_disp_seq . '/' . $row['disp_seq'];
		}

		function checkDuplicateByName($node_id, $node_name) {
			$node_id = $this->db->real_escape_string($node_id);
			$node_name = $this->db->real_escape_string($node_name);

			$sql = "select count(*) cnt
					from %VIEW%
					where binary node_name='$node_name'
					and node_id != '$node_id'
					and del_flag = '0'
					and parent_node=(
						select parent_node
						from %VIEW%
						where node_id = '$node_id'
						and del_flag = '0'
					)";

			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['cnt'];
		}

		function checkDuplicateById($parent_node_id, $node_id_array) {
			$parent_node_id = $this->db->real_escape_string($parent_node_id);

			for($nodes='', $i=0; $i<count($node_id_array); $i++) {
				if($nodes) $nodes.= ',';
				$nodes.= "'" . $this->db->real_escape_string($node_id_array[$i]) . "'";
			}

			$sql = "select node_name, count(*) cnt
					from %VIEW%
					where node_id in ($nodes)
					group by node_name
					having cnt > 1";

			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if($row) return $row;

			$sql = "select count(*) cnt from
						(select node_id, node_name
						from %VIEW%
						where parent_node='$parent_node_id') a,
						(select node_id, node_name
						from %VIEW%
						where node_id in ($nodes))b
					where a.node_id <> b.node_id
					and a.node_name = b.node_name";

			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['cnt'];
		}

		function nodeCount() {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$count+= $this->node[$key]->nodeCount();
				}
			}
			return $count + 1;
		}

		function fileSize() {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$size+= $this->node[$key]->fileSize();
				}
			}
			return $size + $this->file_size;
		}

		function serialize($mode, &$data, $path='') {
			if($mode == 'c' && $this->node_status == '9') return;

			if($path) $path.= '/';
			$mypath = $path . $this->node_name;

			if(is_array($this->node)) {
				$data[$mypath] = '';
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->serialize($mode, $data, $mypath);
				}
			}
			else if($this->parent && $this->node_type != 'folder') {
				$info = pathinfo($mypath);
				$data[$mypath] = $this->contents_id . '.' . $info['extension'];
			}
		}

		function serializeForDownload(&$data, $path='') {
			if($path) $path.= '/';
			$mypath = $path . $this->node_name;

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->serializeForDownload($data, $mypath);
				}
			}
			else if($this->parent) {
				$info = pathinfo($mypath);
				if($this->node_type != 'folder') {
					$data[$mypath] = $this->contents_id . '.' . $info['extension'];
				}
				else {
					$data[$mypath] = '';
				}
			}
		}

		function isMyChild($node_id) {
			$roots = $this->getRoots($node_id);

			if(is_array($roots)) {
				foreach($roots as $value) {
					if($value == $this->node_id) return true;
				}
			}

			return false;
		}

		function isMyParent($node_id) {
			if($this->node_id == $node_id) return true;
			$roots = $this->getRoots($this->node_id);

			if(is_array($roots)) {
				foreach($roots as $value) {
					if($value == $node_id) return true;
				}
			}

			return false;
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

		function getRoots($node_id) {
			$row = $this->selectNode($node_id);
			$roots = explode('/', $row['path']);
			return $roots;
		}

		function getStatus($node_id) {
			$roots = $this->getRoots($node_id);
			$roots[] = $node_id;
			foreach($roots as $node_id) {
				$row = $this->selectNode($node_id);
				if($row['node_status']) return $row['node_status'];
			}
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
