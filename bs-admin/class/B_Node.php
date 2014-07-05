<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Node
	// 
	// Error No: 0	DB error
	// 		   : 1	paste(or move) node to sub folder
	// 		   : 2	node count is not much
	// 		   : 3	node was updated by other user
	// -------------------------------------------------------------------------
	class B_Node {
		function __construct($db, $table, $view, $version, $revision, $node_id, $parent, $expand_level, $open_nodes, $level=0, $row=null) {
			$this->db = $db;
			$this->table = $table;
			$this->view = $view;
			$this->version = $version;
			$this->revision = $revision;
			$this->node_id = $node_id;
			$this->status = true;
			$this->error_no = 0;
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

			$rs = $this->selectChild($node_id);

			while($row = $this->db->fetch_assoc($rs)) {
				$this->node_count++;
				if($row['node_type'] == 'folder') {
					$this->folder_count++;
				}
				if((is_array($open_nodes) && $open_nodes[$node_id]) || ($expand_level === 'all' || $level < $expand_level)) {
					$object = new B_Node($db
										, $this->table
										, $view
										, $version
										, $revision
										, $row['node_id']
										, $this
										, $expand_level
										, $open_nodes
										, $level+1
										, $row);
					$this->addNodes($object);
				}
			}
		}

		function addNodes(&$object) {
			$this->node[] = $object;
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

		function getNodeList($node_id='', $category='', $dir='', $path='') {
			$list = $this->_getNodeList($node_id, $category, $dir, $path);

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$child_list[] = $this->node[$key]->getNodeList($node_id, $category, $dir, $list['path']);
				}
				$list['children'] = $child_list;
			}
			return $list;
		}

		function _getNodeList($node_id, $category, $dir, $path) {
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
			$list['create_datetime'] = date('Y/m/d H:i', $this->create_datetime);
			if($dir) {
				$list['file_size'] = B_Util::human_filesize($this->getFileSize($dir), 1);
				$list['image_size'] = $this->getImageSize($dir);
			}
			$list['level'] = $this->level;
			$list['disp_seq'] = $this->disp_seq;
			if($this->node_id == $node_id) {
				$list[$category] = true;
			}
			if($path) {
				$path.= '/';
			}
			$list['path'] = $path . $list['node_name'];
			return $list;
		}

		function getSelectNodeList() {
			$list = array();

			if($this->node_id != 'root') {
				for($i=1, $indent='' ; $i<$this->level ; $i++) $indent.= '&emsp;';	// indent
				$list[$this->node_id] = $indent . mb_convert_encoding($this->node_name, 'UTF-8', 'auto');
			}
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$list += $this->node[$key]->getSelectNodeList();
				}
			}
			return $list;
		}

		function getPath() {
			$sql_org = "select * from %VIEW% where node_id = '%parent_node%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql_org);

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

		function getImageSize($dir) {
			if($this->node_type == 'folder') return;

			$info = pathinfo($this->node_name);
			switch(strtolower($info['extension'])) {
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				if(file_exists($dir . $this->contents_id . '.' . $info['extension'])) {
					$size = getimagesize($dir . $this->contents_id . '.' . $info['extension']);
					return $size[0] . 'x' . $size[1];
				}
			}
		}

		function arias($destination_node_id, $user_id) {
			if(!$this->node_id) return;

			$node_name = $this->getNewNodeName($destination_node_id, $this->node_name, 'arias');

			$param['parent_node'] = $destination_node_id;
			$param['disp_seq'] = $this->getMaxDispSeq($destination_node_id);
			$param['node_type'] = 'arias';
			$param['node_class'] = $this->node_class;
			$param['node_name'] = $node_name;
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
			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->arias($node_id, $user_id);
					if(!$ret) return $ret;
				}
			}

			return true;
		}

		function copy($destination_node_id, $user_id, $call_back=null) {
			if(!$this->node_id) return;

			// if destination node is my child
			if($this->isMyChild($destination_node_id)) {
				$this->error_no = 1;
				return false;
			}

			if($call_back) {
				if(!$ret = $this->callBack($call_back)) return $ret;
			}

			$node_name = $this->getNewNodeName($destination_node_id, $this->node_name, 'copy');

			$param['parent_node'] = $destination_node_id;
			$param['disp_seq'] = $this->getMaxDispSeq($destination_node_id);
			$param['node_type'] = $this->node_type;
			$param['node_class'] = $this->node_class;
			$param['node_name'] = $node_name;
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
			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->copy($node_id, $user_id, $call_back);
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

			$param['parent_node'] = $destination_node_id;
			$param['node_id'] = $this->node_id;
			if(!$disp_seq) $disp_seq = $this->getMaxDispSeq($destination_node_id);
			$param['disp_seq'] = $disp_seq;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->update($param);
		}

		function delete() {
			if(!$this->node_id) return;

			if(count($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->delete();
					if(!$ret) return $ret;
				}
			}
			$this->cloneNode($this->node_id);

			$param['node_id'] = $this->node_id;
			$param['del_flag'] = '1';
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->update($param);
		}

		function insert($node_type, $node_class, $user_id, &$new_node_id) {
			if(!$this->node_id) return;

			$default_node_name = $this->script['bframe_tree']['icon'];
			$node_name = $this->getNewNodeName($this->node_id, $default_node_name[$node_type]['new'], 'insert');
			$param['parent_node'] = $this->node_id;
			$param['node_type'] = $node_type;
			$param['node_class'] = $node_class;
			$param['node_name'] = $node_name;
			$param['disp_seq'] = $this->getMaxDispSeq($this->node_id);
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
			$sql = "select node_name from %VIEW% where parent_node='$parent_node' order by node_name";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$rs = $this->db->query($sql);

			for($cnt=0 ; $row[$cnt] = $this->db->fetch_assoc($rs); $cnt++);

			$row_data = print_r($row, true);

			for($i=2, $node_name = $default_name;; $node_name = $prefix . $default_name . $extend) {
				for($j=0 ; $j<$cnt && $row[$j]['node_name'] != $node_name; $j++);

				if($j == $cnt) {
					break;
				}
				switch($mode) {
				case 'insert':
					$extend = '(' . $i++ . ')';
					break;

				case 'copy':
					$prefix.= 'copy_of_';
					break;

				case 'arias':
					$prefix.= 'arias_of_';
					break;
				}
			}
			return $node_name;
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

			for($i=0 ; $i<count($request['node_list']) ; $i++) {
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

			for($i=0 ; $i<count($request['node_list']) ; $i++) {
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
			for($i=0 ; $i<count($request['node_list']) ; $i++) {
				$this->cloneNode($request['node_list'][$i]);

				$param['node_id'] = $request['node_list'][$i];
				$param['parent_node'] = $this->node_id;
				$param['disp_seq'] = $i;
				$param['update_user'] = $user_id;
				$param['update_datetime'] = time();
				$param['version_id'] = $this->version;
				$param['revision_id'] = $this->revision;

				$ret = $this->tbl_node->update($param);
				if(!$ret) return $ret;
			}
			return true;
		}

		function saveName($node_name, $user_id) {
			if(!$this->node_id) return;

			$this->cloneNode($this->node_id);

			$param['node_id'] = $this->node_id;
			$param['node_name'] = $node_name;
			$param['update_user'] = $user_id;
			$param['update_datetime'] = time();
			$param['version_id'] = $this->version;
			$param['revision_id'] = $this->revision;

			return $this->tbl_node->update($param);
		}

		function getMaxDispSeq($parent_node_id) {
			$sql = "select ifnull(max(disp_seq)+1, 0) disp_seq from %VIEW% where parent_node='%PARENT_NODE%'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);
			$sql = str_replace('%PARENT_NODE%', $parent_node_id, $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['disp_seq'];
		}

		function checkDuplicateByName($node_id, $node_name) {
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

		function checkDuplicateById($parent_node_id, $node_id) {
			for($nodes='', $i=0 ; $i<count($node_id) ; $i++) {
				if($nodes) $nodes.= ',';
				$nodes.= "'" . $node_id[$i] . "'";
			}

			$sql = "select node_name, count(*) cnt
					from %VIEW%
					where node_id in (%NODES%)
					group by node_name
					having cnt > 1";

			$sql = str_replace('%NODES%', $nodes, $sql);
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
						where node_id in (%NODES%))b
					where a.node_id <> b.node_id
					and a.node_name = b.node_name";

			$sql = str_replace('%NODES%', $nodes, $sql);
			$sql = str_replace('%VIEW%', B_DB_PREFIX . $this->view, $sql);

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			return $row['cnt'];
		}

		function serialize(&$data, $path='') {
			if($path) $path.= '/';
			$mypath = $path . $this->node_name;

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->serialize($data, $mypath);
				}
			}
			else if($this->parent && $this->node_type != 'folder') {
				$info = pathinfo($mypath);
				$data[$mypath] = $this->contents_id . '.' . strtolower($info['extension']);
				$data[$this->node_id] = $mypath;
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
					$data[$mypath] = $this->contents_id . '.' . strtolower($info['extension']);
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
			$roots = $this->getRoots($this->parent);

			if(is_array($roots)) {
				foreach($roots as $value) {
					if($value == $node_id) return true;
				}
			}

			return false;
		}

		function getRoots($node_id) {
			if(!$this->roots) {
				$i=0;
				$this->roots[$i++] = $node_id;

				for($id = $node_id; $row = $this->selectNode($id) ; $id = $row['parent_node']) {
					$this->roots[$i++] = $row['parent_node'];
				}
			}

			return $this->roots;
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
