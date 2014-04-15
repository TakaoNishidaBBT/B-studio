<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_FileNode
	// 
	// -------------------------------------------------------------------------
	class B_FileNode {
		function __construct($dir, $path, $open_nodes=null, $parent=null, $expand_level=0, $level=0, $thumb_info=null) {
			if(!$path) return;

			$this->dir = $dir;
			$this->path = $path == 'root' ? '' : $path;
			$this->node_id = $path == '/' ? 'root' : $path;

			$this->fullpath = B_Util::getPath($dir, $this->path);
			$i = strrpos($this->fullpath, '/');
			if($i) {
				$this->file_name = substr($this->fullpath, $i+1);
			}

			$this->parent = $parent;
			$this->level = $level;
			$this->node_count = 0;

			if(!$thumb_info	&& file_exists(B_FILE_INFO_THUMB)) {
				$serializedString = file_get_contents(B_FILE_INFO_THUMB);
			    $thumb_info = unserialize($serializedString);
			}
			$this->thumb_info = $thumb_info;
			$this->thumbnail_image_path = $this->getThumbnailImgPath();
			$this->thumb = $this->thumb_info[$this->thumbnail_image_path];

			if(!file_exists($this->fullpath)) return;

			$this->update_datetime = date('Y/m/d H:i', filemtime($this->fullpath));
			if(!is_dir($this->fullpath)) {
				$this->file_size = filesize($this->fullpath);
				$this->node_type = 'file';
				$this->node_class = 'leaf';
				$image_size = getimagesize($this->fullpath);
				if(is_array($image_size)) {
					$this->image_size = $image_size[0] . 'x' . $image_size[1];
				}

				return;
			}

			if($this->node_id == 'root') {
				$this->node_type = 'root';
			}
			else {
				$this->node_type = 'folder';
			}
			$this->node_class = 'folder';

			$handle = opendir($this->fullpath);

			while(false !== ($file_name = readdir($handle))){
				if($file_name == '.' || $file_name == '..') continue;

				$this->node_count++;

				if(is_dir(B_Util::getPath($this->fullpath, $file_name))) {
					$this->folder_count++;
				}
				if((is_array($open_nodes) && $open_nodes[$this->node_id]) || ($expand_level === 'all' || $level < $expand_level)) {
					$object = new B_FileNode($this->dir, B_Util::getPath($this->path, $file_name), $open_nodes, $this, $expand_level, $level+1);
					$this->addNodes($object);
				}
			}

			$this->sort();

			closedir($handle);
		}

		function addNodes($object) {
			$this->node[] = &$object;
		}

		function setConfig($config) {
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}

		function sort() {
			if(is_array($this->node)) {
				uasort($this->node, array($this,'_sort_name_callback'));
			}
		}

		function _sort_name_callback($a, $b) {
			if($a->node_type == $b->node_type) {
				$ret = ($a->file_name < $b->file_name) ? -1 : 1;
			}
			else {
				$ret = ($a->node_type > $b->node_type) ? -1 : 1;
			}
			return $ret;
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

		function walk($obj, $method) {
			if(method_exists($obj, $method)) {
				$obj->$method($this);
			}

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->walk($obj, $method);
				}
			}
			return;
		}

		function getNodeList($node_id='', $category='', $path='', $disp_seq=0) {
			$list = $this->_getNodeList($node_id, $category, $path, $disp_seq);

			if(is_array($this->node)) {
				$disp_seq=0;
				foreach(array_keys($this->node) as $key) {
					$child_list[] = $this->node[$key]->getNodeList($node_id, $category, $list['path'], $disp_seq++);
				}
				$list['children'] = $child_list;
			}
			return $list;
		}

		function _getNodeList($node_id, $category, $path, $disp_seq) {
			$list['node_id'] = $this->node_id;
			$list['node_type'] = $this->node_type;
			$list['node_class'] = $this->node_class;
			$list['node_name'] = $this->file_name;
			$list['thumbnail_image_path'] = $this->thumbnail_image_path;
			$list['node_count'] = $this->node_count;
			$list['folder_count'] = $this->folder_count;
			$list['update_datetime'] = $this->update_datetime;
			$list['create_datetime'] = $this->update_datetime;
			$list['file_size'] = B_Util::human_filesize($this->file_size, 1);
			if($this->image_size) {
				$list['image_size'] = $this->image_size;
			}
			if($this->node_id == $node_id) {
				$list[$category] = true;
			}
			if($path) {
				$path.= '/';
			}
			$list['path'] = $path . $list['node_name'];
			$list['disp_seq'] = $disp_seq;
			return $list;
		}

		function rename($old_name, $new_name) {
			if($this->node_id == $old_name) {
				if(strtolower($this->node_id) == strtolower($new_name)) {
					// windows server can't rename upper-lower case only
					$ret = rename(B_Util::getPath(B_UPLOAD_DIR, $this->node_id), B_Util::getPath(B_UPLOAD_DIR , $new_name . 'tmp___tmp'));
					$ret = rename(B_Util::getPath(B_UPLOAD_DIR , $new_name . 'tmp___tmp'), B_Util::getPath(B_UPLOAD_DIR , $new_name));
				}
				else {
					$ret = rename(B_Util::getPath(B_UPLOAD_DIR, $this->node_id), B_Util::getPath(B_UPLOAD_DIR , $new_name));
				}
				if(!$ret) return false;

				$this->node_id = $new_name;
				$this->path = $new_name;
			}
			else {
				$this->path = B_Util::getPath($this->parent->path, $this->file_name);
				$this->node_id = B_Util::getPath($this->parent->path, $this->file_name);
			}

			$this->fullpath = B_Util::getPath($this->dir, $this->path);

			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$ret = $this->node[$key]->rename($old_name, $new_name);
					if(!$ret) return false;
				}
			}
			return true;
		}

		function copy($destination, $recursive=false) {
			if(file_exists($this->fullpath)) {
				if(is_dir($this->fullpath)) {
					$destination = B_Util::getPath($destination, $this->file_name);
					if(!file_exists($destination)) {
						mkdir($destination);
						chmod($destination, 0777);
					}
				}
				else {
					copy($this->fullpath, B_Util::getPath($destination, $this->file_name));
					chmod($this->fullpath, 0777);
				}
			}
			if($recursive && is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->copy($destination, $recursive);
				}
			}

			return true;
		}

		function move($source, $destination) {
			return rename($source, $destination);
		}

		function remove() {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->remove();
				}
			}
			if(is_dir($this->fullpath)) {
				rmdir($this->fullpath);

			}
			else if(file_exists($this->fullpath)) {
				unlink($this->fullpath);
				if(file_exists(B_UPLOAD_THUMBDIR . $this->thumb) && !is_dir(B_UPLOAD_THUMBDIR . $this->thumb)) {
					unlink(B_UPLOAD_THUMBDIR . $this->thumb);
				}
			}

			return true;
		}

		function createFolder($node_name, &$new_node_id) {
			if(!is_dir($this->fullpath)) {
				return false;
			}

			for($i=2, $folder = $node_name; file_exists(B_Util::getPath($this->fullpath, $folder)); $folder = $node_name . $extend) {
				$extend = '(' . $i++ . ')';
			}
			$folder_name = B_Util::getPath($this->fullpath, $folder);
			$ret = mkdir($folder_name);
			chmod($folder_name, 0777);

			$new_node_id = B_Util::getPath($this->path, $folder);

			return $ret;
		}

		function getMaxThumbnailNo() {
			$handle = opendir(B_UPLOAD_THUMBDIR);
			while(false !== ($file_name = readdir($handle))){
				if($file_name == '.' || $file_name == '..') continue;
				$number = substr($file_name, 0, 10);
				if(!$max || intval($max) < intval($number)) {
					$max = $number;
				}
			}
			return $max;
		}

		function createthumbnail(&$data, &$index=0) {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->createthumbnail($data, $index);
				}
			}
			else if($this->node_type != 'folder' && $this->node_type != 'root') {
				$this->_createthumbnail($data, $index);
			}
		}

		function getThumbnailImgPath() {
			$file_info = pathinfo($this->path);
			if($file_info['dirname'] != '.' && $file_info['dirname'] != '\\') {
				return B_Util::getPath(B_Util::getPath(B_UPLOAD_URL, $file_info['dirname']), B_THUMB_PREFIX . $file_info['basename']);
			}
			else {
				return B_Util::getPath(B_UPLOAD_URL, B_THUMB_PREFIX . $file_info['basename']);
			}
		}

		function _createthumbnail(&$data, &$index) {
			if(!file_exists($this->fullpath)) return;
			$max_size = B_THUMB_MAX_SIZE;
			$file_info = pathinfo($this->path);
			$thumbnail_image_path = $this->getThumbnailImgPath();

			if($this->thumb && file_exists(B_UPLOAD_THUMBDIR . $this->thumb)) {
				$data[$thumbnail_image_path] = $this->thumb;
			}
			else {
				$index++;
				$thumbnail_file_path = B_UPLOAD_THUMBDIR . str_pad($index, 10, '0', STR_PAD_LEFT) . '.' . $file_info['extension'];

				switch(strtolower($file_info['extension'])) {
				case 'jpg':
				case 'jpeg':
					if(!function_exists('imagecreatefromjpeg')) return;
					$image = @imagecreatefromjpeg($this->fullpath);
					break;

				case 'gif':
					if(!function_exists('imagecreatefromgif')) return;
					$image = @imagecreatefromgif($this->fullpath);
					break;

				case 'png':
					if(!function_exists('imagecreatefrompng')) return;
					$image = @imagecreatefrompng($this->fullpath);
					break;

				default:
					return;
				}

				$image_size = getimagesize($this->fullpath);
				$width = $image_size[0];
				$height = $image_size[1];

				if($width > $max_size) {
					if($width > $height) {
						$height = round($height * $max_size / $width);
						$width = $max_size;
					}
					else {
						$width = round($width * $max_size / $height);
						$height = $max_size;
					}
				}
				else if($height > $max_size) {
					$width = round($width * $max_size / $height);
					$height = $max_size;
				}
				if(!$width) $width=1;
				if(!$height) $height=1;

				$new_image = ImageCreateTrueColor($width, $height);
				ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);

				switch(strtolower($file_info['extension'])) {
				case 'jpg':
				case 'jpeg':
					ImageJPEG($new_image, $thumbnail_file_path, 100);
					break;

				case 'gif':
					ImageGIF($new_image, $thumbnail_file_path);
					break;

				case 'png':
					ImagePNG($new_image, $thumbnail_file_path);
					break;

				default:
					return;
				}
				chmod($thumbnail_file_path, 0777);
				$data[$thumbnail_image_path] = str_pad($index, 10, '0', STR_PAD_LEFT) . '.' . $file_info['extension'];
			}

			return;
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

		function serializeForDownload(&$data) {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->serializeForDownload($data);
				}
			}
			else if($this->parent) {
				if(substr($this->path, 0, 1) == '/') {
					$path = substr($this->path, 1);
				}
				if($this->node_type != 'folder' && $this->node_type != 'root') {
					$data[$path] = $this->fullpath;
				}
				else {
					$data[$path] = '';
				}
			}
		}
	}
