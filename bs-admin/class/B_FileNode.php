<?php
/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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

			$this->update_datetime_u = filemtime($this->fullpath);
			$this->update_datetime_t = date('Y/m/d H:i', filemtime($this->fullpath));
			if(!is_dir($this->fullpath)) {
				$this->file_size = filesize($this->fullpath);
				$this->node_type = 'file';
				$this->node_class = 'leaf';
				$image_size = getimagesize($this->fullpath);
				if(is_array($image_size)) {
					$this->image_size = $image_size[0] * $image_size[1];
					$this->human_image_size = $image_size[0] . 'x' . $image_size[1];
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

			while(false !== ($file_name = readdir($handle))) {
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

			$this->sort('primary');

			closedir($handle);
		}

		function addNodes(&$object) {
			$this->node[] = $object;
		}

		function removeNodes(&$object) {
			unset($this->node[array_search($object, $this->node)]);
		}

		function setConfig($config) {
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}

		function sort($type=null) {
			if(!is_array($this->node)) return;
			if($type == 'primary') {
				usort($this->node, array($this,'_sort_name_callback'));
			}
			else {
				uasort($this->node, array($this,'_sort_name_callback'));
			}
			$i=0;
			foreach($this->node as &$value) {
				$value->order = $i++;
			}
			ksort($this->node);
		}

		function _sort_name_callback($a, $b) {
			$key = $this->sort_key ? $this->sort_key : 'file_name';
			$order = $this->sort_order ? $this->sort_order : 'asc';

			if($a->node_type == $b->node_type) {
				if($order == 'asc') {
					$ret = ($a->$key < $b->$key) ? -1 : 1;
				}
				else {
					$ret = ($a->$key >= $b->$key) ? -1 : 1;
				}
			}
			else {
				if($order == 'asc') {
					$ret = ($a->node_type > $b->node_type) ? -1 : 1;
				}
				else {
					$ret = ($a->node_type <= $b->node_type) ? -1 : 1;
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
				if($this->sort_key) {
					$this->sort();
				}
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
			$list['create_datetime_u'] = $this->update_datetime_u;
			$list['create_datetime_t'] = $this->update_datetime_t;
			$list['update_datetime_u'] = $this->update_datetime_u;
			$list['update_datetime_t'] = $this->update_datetime_t;
			if($this->node_type != 'folder') {
				$list['file_size'] = $this->file_size;
				$list['human_file_size'] = B_Util::human_filesize($this->file_size, 'K');
			}
			if($this->image_size) {
				$list['image_size'] = $this->image_size;
				$list['human_image_size'] = $this->human_image_size;
			}
			if($this->node_id == $node_id) {
				$list[$category] = true;
			}
			if($path && substr($path, -1) != '/') {
				$path.= '/';
			}
			$list['path'] = $path . $list['node_name'];
			$list['disp_seq'] = $disp_seq;
			$list['order'] = $this->order;
			return $list;
		}

		function rename($old_name, $new_name) {
			if($this->node_id === $old_name) {
				$ret = rename(B_Util::getPath($this->dir, $old_name), B_Util::getPath($this->dir , $new_name));
				if(!$ret) return false;

				$this->node_id = $new_name;
				$this->path = $new_name;
				$this->thumbnail_image_path = $this->getThumbnailImgPath();
			}
			else {
				$this->path = B_Util::getPath($this->parent->path, $this->file_name);
				$this->node_id = B_Util::getPath($this->parent->path, $this->file_name);
				$this->thumbnail_image_path = $this->getThumbnailImgPath();
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

		function copy($destination, &$new_node_name, $recursive=false) {
			if($this->isMyChild($destination)) {
				$this->error_no = 1;
				return false;
			}

			if(file_exists($this->fullpath)) {
				if(is_dir($this->fullpath)) {
					$new_node_name = $this->getNewNodeName($destination, $this->file_name, 'copy');
					$destination = B_Util::getPath($destination, $new_node_name);
					if(!file_exists($destination)) {
						mkdir($destination);
						chmod($destination, 0777);
					}
				}
				else {
					$new_node_name = $this->getNewNodeName($destination, $this->file_name, 'copy');
					copy($this->fullpath, B_Util::getPath($destination, $new_node_name));
					chmod(B_Util::getPath($destination, $new_node_name), 0777);
				}
			}
			if($recursive && is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->copy($destination, $recursive);
				}
			}

			return true;
		}

		function _copy($destination, $recursive=false) {
			if(file_exists($this->fullpath)) {
				if(is_dir($this->fullpath)) {
					$destination = B_Util::getPath($destination, $this->file_name);
					if(!file_exists($destination)) {
						mkdir($destination);
						chmod($destination, 0777);
					}
				}
				else {
					$destination = B_Util::getPath($destination, $this->file_name);
					copy($this->fullpath, $destination);
					chmod($destination, 0777);
				}
			}
			if($recursive && is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->_copy($destination, $recursive);
				}
			}

			return true;
		}

		function move($source) {
			if($this->isMyParent($source->fullpath)) {
				$this->error_no = 1;
				return false;
			}

			try {
				if(file_exists($this->fullpath)) {
					if(is_dir($this->fullpath)) {
						$info = pathinfo($source->fullpath);
						$source->parent->removeNodes($source);
						$this->addNodes($source);
						$source->parent = $this;
						$this->rename($source->node_id, B_Util::getPath($this->path, $source->file_name));
						return true;
					}
				}
			}
			catch(Exception $e) {
				return false;
			}
		}

		function remove() {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->remove();
				}
			}
			if(is_dir($this->fullpath)) {
				usleep(1000);
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

		function removeChild() {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->remove();
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

		function createFile($node_name, &$new_node_id) {
			if(!is_dir($this->fullpath)) {
				return false;
			}
			$new_node_name = $this->getNewNodeName($this->fullpath, $node_name, 'insert');
			$file_name = B_Util::getPath($this->fullpath, $new_node_name);
			$fp = fopen($file_name, 'w');
			fclose($fp);
			chmod($file_name, 0666);

			$new_node_id = B_Util::getPath($this->path, $new_node_name);

			return true;
		}

		function getNewNodeName($dir, $default_name, $mode) {
			$info = pathinfo($default_name);

			for($i=2, $node_name = $info['filename'];; $node_name = $prefix . $info['filename'] . $extend) {
				if($info['extension']) {
					if(!file_exists(B_Util::getPath($dir, $node_name) . '.' . $info['extension'])) break;
				}
				else {
					if(!file_exists(B_Util::getPath($dir, $node_name))) break;
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

		function isMyChild($path) {
			$path_array = explode('/', $path);

			for($i=0, $dir=$path; $i<count($path_array); $dir = dirname($dir), $i++) {
				if($this->fullpath == $dir) {
					return true;
				}
			}

			return false;
		}

		function isMyParent($path) {
			$path_array = explode('/', $path);

			for($i=0, $dir=$this->fullpath; $i<count($path_array); $dir = dirname($dir), $i++) {
				if($path == $dir) {
					return true;
				}
			}
			return false;
		}

		function getMaxThumbnailNo() {
			$handle = opendir(B_UPLOAD_THUMBDIR);
			while(false !== ($file_name = readdir($handle))){
				if($file_name == '.' || $file_name == '..') continue;
				$number = substr($file_name, 0, 10);
				if(!is_numeric($number)) continue;
				if(!$max || intval($max) < intval($number)) {
					$max = $number;
				}
			}
			return $max;
		}

		function createthumbnail(&$data, &$index=0, $callback=null) {
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->createthumbnail($data, $index, $callback);
				}
			}
			else if($this->node_type != 'folder' && $this->node_type != 'root') {
				$ret = $this->_createthumbnail($data, $index);
			}
			if($callback && $ret) {
				$this->callBack($callback);
			}
		}

		function getThumbnailImgPath() {
			$file_info = pathinfo($this->path);
			if(strtolower($file_info['extension']) != 'svg') {
				$thumb_prefix = B_THUMB_PREFIX;
			}
			if($file_info['dirname'] != '.' && $file_info['dirname'] != '\\') {
				return B_Util::getPath(B_Util::getPath(B_UPLOAD_URL, $file_info['dirname']), $thumb_prefix . $file_info['basename']);
			}
			else {
				return B_Util::getPath(B_UPLOAD_URL, $thumb_prefix . $file_info['basename']);
			}
		}

		function _createthumbnail(&$data, &$index) {
			if(!file_exists($this->fullpath)) return;

			if($this->thumb && file_exists(B_UPLOAD_THUMBDIR . $this->thumb)) {
				$data[$this->thumbnail_image_path] = $this->thumb;
				return;
			}

			$file_info = pathinfo($this->path);
			$source_file_path = $this->fullpath;

			switch(strtolower($file_info['extension'])) {
			case 'jpg':
			case 'jpeg':
				if(!function_exists('imagecreatefromjpeg')) return;
				$image = @imagecreatefromjpeg($source_file_path);
				break;

			case 'gif':
				if(!function_exists('imagecreatefromgif')) return;
				$image = @imagecreatefromgif($source_file_path);
				break;

			case 'png':
				if(!function_exists('imagecreatefrompng')) return;
				$image = @imagecreatefrompng($source_file_path);
				break;

			case 'bmp':
				$image = B_Util::imagecreatefrombmp($source_file_path);
				break;

			case 'avi':
			case 'flv':
			case 'mov':
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'wmv':
				$source_file_path = $this->createMovieThumbnail($source_file_path);
				if(!function_exists('imagecreatefromjpeg')) return;
				$image = @imagecreatefromjpeg($source_file_path);
				break;

			default:
				return;
			}

			$index++;
			$thumbnail_file_path = str_pad($index, 10, '0', STR_PAD_LEFT) . '.' . $file_info['extension'];
			$image_size = getimagesize($source_file_path);
			$width = $image_size[0];
			$height = $image_size[1];
			$max_size = B_THUMB_MAX_SIZE;

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
			$new_image = imagecreatetruecolor($width, $height);
			ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);

			switch(strtolower($file_info['extension'])) {
			case 'jpg':
			case 'jpeg':
			case 'bmp':
				imagejpeg($new_image, B_UPLOAD_THUMBDIR . $thumbnail_file_path, 100);
				break;

			case 'gif':
				imagegif($new_image, B_UPLOAD_THUMBDIR . $thumbnail_file_path);
				break;

			case 'png':
				imagepng($new_image, B_UPLOAD_THUMBDIR . $thumbnail_file_path);
				break;

			case 'avi':
			case 'flv':
			case 'mov':
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'wmv':
				$thumbnail_file_path = str_pad($index, 10, '0', STR_PAD_LEFT) . '.jpg';
				imagejpeg($new_image, B_UPLOAD_THUMBDIR . $thumbnail_file_path, 100);
				unlink($source_file_path);
				break;

			default:
				return;
			}
			chmod(B_UPLOAD_THUMBDIR . $thumbnail_file_path, 0777);
			$data[$this->thumbnail_image_path] = $thumbnail_file_path;

			return true;
		}

		function createMovieThumbnail($filename) {
			$ffmpeg = FFMPEG;
			$output = B_RESOURCE_WORK_DIR . time() . 'tmp.jpg';
			if(substr(PHP_OS, 0, 3) === 'WIN') {
				$cmdline = "$ffmpeg -ss 3 -i $filename -f image2 -vframes 1 $output 2>&1";
				$p = popen($cmdline, 'r');
				if($p) {
		            pclose($p);
				}
				else {
					$this->log->write('error');
				}
			}
			else {
				$cmdline = "$ffmpeg -ss 3 -i $filename -f image2 -vframes 1 $output";
				exec("$cmdline > /dev/null");
			}
			return $output;
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

		function serializeForDownload(&$data, $path='') {
			if($path) $path.= '/';
			$mypath = $path . $this->file_name;
			if(is_array($this->node)) {
				foreach(array_keys($this->node) as $key) {
					$this->node[$key]->serializeForDownload($data, $mypath);
				}
			}
			else {
				if(substr($this->path, 0, 1) == '/') {
					$path = substr($this->path, 1);
				}
				if($this->node_type != 'folder' && $this->node_type != 'root') {
					$data[$mypath] = $this->fullpath;
				}
				else {
					$data[$mypath] = '';
				}
			}
		}

		function nodes_count() {
			if(is_array($this->node)) {
				$count = count($this->node);
				foreach(array_keys($this->node) as $key) {
					$count += $this->node[$key]->nodes_count();
				}
			}
			return $count;
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
