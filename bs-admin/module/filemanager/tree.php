<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class filemanager_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->dir = B_UPLOAD_DIR;

			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_FileNode($this->dir, '');

			$this->tree->setConfig($this->tree_config);

			$this->status = true;
			if(!$this->session['sort_order']) $this->session['sort_order'] = 'asc';
			if(!$this->session['sort_key']) $this->session['sort_key'] = 'file_name';
		}

		function getNodeList() {
			$this->session['selected_node'] = '';

			if($this->request['sort_key']) {
				if($this->request['node_id'] == $this->session['current_node'] && $this->session['sort_key'] == $this->request['sort_key']) {
					$this->session['sort_order'] = $this->session['sort_order'] == 'asc' ? 'desc' : 'asc';
				}
				
				$this->session['sort_key'] = $this->request['sort_key'];
			}
			if($this->request['node_id'] && $this->request['mode'] != 'open') {
				$this->session['current_node'] = $this->request['node_id'];
			}
			if($this->request['node_id']) {
				$this->session['open_nodes'][$this->request['node_id']] = true;
			}
			if(!$this->session['current_node']) {
				$this->session['current_node'] = 'root';
			}
			if(isset($this->request['display_mode'])) {
				$this->session['display_mode'] = $this->request['display_mode'];
			}
			$this->response($this->session['current_node'], 'select');

			exit;
		}

		function closeNode() {
			$this->session['open_nodes'][$this->request['node_id']] = false;
			$this->session['selected_node'] = '';

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo json_encode($response);

			exit;
		}

		function pasteNode() {
			$root = new B_FileNode($this->dir, 'root', null, null, 'all');
			$dest = $root->getNodeById($this->request['destination_node_id']);

			switch($this->request['mode']) {
			case 'copy':
				// Set time limit to 5 minutes
				set_time_limit(300);

				$this->session['selected_node'] = '';

				$this->total_copy_nodes = 0;
				$this->copy_nodes = 0;
				foreach($this->request['source_node_id'] as $node_id) {
					$node = $root->getNodeById($node_id);
					if($node->isMyChild($dest->fullpath)) {
						$this->message = $this->getErrorMessage(1);
						$this->status = false;
						break;
					}
					$this->total_copy_nodes += $node->nodeCount(); 
					$source_node[] = $node;
				}
				if(!$this->status) break;

				if($this->total_copy_nodes) {
					// send progress
					header('Content-Type: application/octet-stream');
					header('Transfer-encoding: chunked');
					flush();
					ob_flush();

					// Send start message
					$response['status'] = 'show';
					$response['message'] = 'Copying ...';
					$response['progress'] = 0;
					$this->progress = 0;
					$this->sendChunk(json_encode($response));
					$this->show_progress = true;

					usleep(1000);
				}

				foreach($source_node as $source) {
					if(!file_exists($source->fullpath)) {
						$this->message = __('Another user has updated this record');
						$this->status = false;
					}
					if(!file_exists($dest->fullpath)) {
						$this->message = __('Another user has updated this record');
						$this->status = false;
					}
					else {
						if($dest->node_type == 'folder' || $dest->node_type == 'root') {
							if($this->show_progress) $callback = array('obj' => $this, 'method' => 'copy_callback');
							$ret = $source->copy($dest->fullpath, $new_node_name, true, $callback);
						}
						if($ret) {
							$this->status = true;
							$this->session['selected_node'][] = $dest->path . '/' . $new_node_name;
						}
						else {
							$this->status = false;
						}
					}
				}
				if($this->status) {
					$root = new B_FileNode($this->dir, 'root', null, null, 'all');
					$this->refreshThumbnailCache($root, $this->show_progress);
				}
				else {
					$this->message = $this->getErrorMessage($source->getErrorNo());

					if($this->show_progress) {
						$response['status'] = 'error';
						$response['message'] = $this->getErrorMessage($source->getErrorNo());
						$this->sendChunk(',' . json_encode($response));
						sleep(1);
					}
				}

				if($this->show_progress) {
					$response['status'] = 'finished';
					$response['progress'] = 100;
					$this->sendChunk(',' . json_encode($response));
					$this->sendChunk();	// terminate
				}
				break;

			case 'cut':
				foreach($this->request['source_node_id'] as $node_id) {
					$source = $root->getNodeById($node_id);

					if(!file_exists($source->fullpath)) {
						$this->message = __('Another user has updated this record');
						$this->status = false;
					}
					if(!file_exists($dest->fullpath)) {
						$this->message = __('Another user has updated this record');
						$this->status = false;
					}
					else if(file_exists($dest->fullpath . '/' . $source->file_name)) {
						$this->message = __('Already exists');
						$this->status = false;
					}
					else {
						if($dest->node_type == 'folder' || $dest->node_type == 'root') {
							$ret = $dest->move($source);
						}
						if($ret) {
							$this->status = true;
							if($this->session['current_node'] == $this->request['node_id']) {
								$this->session['current_node'] = $new_node_id;
							}
							$this->session['open_nodes'][$this->request['node_id']] = false;
							$this->session['open_nodes'][$new_node_id] = true;
						}
						else {
							$this->status = false;
						}
					}
				}
				if($this->status) {
					$this->refreshThumbnailCache($root);
				}
				else if(!$this->message) {
					$this->message = $this->getErrorMessage($dest->getErrorNo());
				}
				break;
			}

			if(!$response) $this->response($this->request['node_id'], 'select');
			exit;
		}

		function copy_callback($file_node) {
			$this->copy_nodes++;

			$response['status'] = 'progress';
			$response['progress'] = round($this->copy_nodes / $this->total_copy_nodes * 100);
			if($this->progress != $response['progress']) {
				$this->sendChunk(',' . json_encode($response));
				$this->progress = $response['progress'];
			}
		}

		function createNode() {
			$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			$node = new B_FileNode($this->dir, $this->request['destination_node_id']);

			if($this->request['node_type'] == 'folder') {
				$ret = $node->createFolder('newFolder', $new_node_id);
			}
			else {
				$ret = $node->createFile('newFile.txt', $new_node_id);
			}

			if($ret) {
				$this->status = true;
				$this->session['selected_node'] = '';
				$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			}
			else {
				$this->status = false;
				$this->message = __('An error has occurred');
			}
			$this->response($new_node_id, 'new_node');
			exit;
		}

		function deleteNode() {
			if($this->request['delete_node_id'] && $this->request['delete_node_id'] != 'null') {
				foreach($this->request['delete_node_id'] as $node_id) {
					$node = new B_FileNode($this->dir, $node_id, null, null, 'all');
					if(!file_exists($node->fullpath)) {
						$this->message = __('Another user has updated this record');
						$this->status = false;
					}
					else {
						$ret = $node->remove();
						if($ret) {
							$this->status = true;
						}
						else {
							$this->status = false;
							$this->message = __('An error has occurred');
							break;
						}
					}
				}
				if($this->status) {
					$root = new B_FileNode($this->dir, 'root', null, null, 'all');
					$this->refreshThumbnailCache($root);
				}
			}

			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$file_info = pathinfo($this->request['node_id']);
				$node_name = trim($this->request['node_name']);
				$new_node_id = B_Util::getPath($file_info['dirname'], $node_name);
				$source = B_Util::getPath($this->dir , $this->request['node_id']);
				$dest = B_Util::getPath($this->dir , $new_node_id);

				if($this->checkFileName($source, $dest, $node_name, $file_info)) {
					$root = new B_FileNode($this->dir, 'root', null, null, 'all');
					$ret = $root->rename($this->request['node_id'], $new_node_id);
					if($ret) {
						$this->status = true;
						$this->session['selected_node'] = '';
						$this->session['selected_node'][0] = $new_node_id;
						if($this->session['current_node'] == $this->request['node_id']) {
							$this->session['current_node'] = $new_node_id;
						}
						$this->session['open_nodes'][$this->request['node_id']] = false;
						$this->session['open_nodes'][$new_node_id] = true;
						$this->refreshThumbnailCache($root);
					}
					else {
						$this->message = __('The name could not be changed');
					}
				}
			}
			$this->response($this->session['current_node'], 'select');
			exit;
		}

		function checkFileName($source, $dest, $file_name, $file_info) {
			$node_type = is_dir($source) ? 'folder' : 'file';

			if(!strlen(trim($file_name))) {
				$this->message = __('Please enter a name for the %ITEM%');
				$this->message = str_replace('%ITEM%', __($node_type), $this->message);
				return false;
			}
			if(strlen($file_name) != mb_strlen($file_name)) {
				$this->message = __('Multi-byte characters cannot be used');
				return false;
			}
			if(!file_exists(B_Util::getPath($this->dir, $file_info['path']))) {
				$this->message = __('Another user has updated this record');
				return false;
			}
			if(file_exists($dest) && strtolower($file_info['basename']) != strtolower($file_name)) {
				$this->message = __('A %ITEM% with this name already exists. Please enter a different name.');
				$this->message = str_replace('%ITEM%', __($node_type), $this->message);
				return false;
			}
			if(preg_match('/[\\\\:\/\*\?<>\|\s]/', $file_name)) {
				$this->message = __('The following charcters cannot be used in file or folder names (\ / : * ? " < > | space)');
				return false;
			}

			return true;
		}

		function refreshThumbnailCache($root, $progress=false) {
			$max = $root->getMaxThumbnailNo();
			if($progress) {
				$this->total_nodes = $root->nodeCount();

				usleep(500000);

				$response['status'] = 'progress';
				$response['progress'] = 0;
				$response['message'] = 'Refreshing...';
				$this->sendChunk(',' . json_encode($response));

				usleep(500000);

				$callback = array('obj' => $this, 'method' => 'createThumbnail_callback');
			}
			$root->createthumbnail($data, $max, $callback);
			$fp = fopen(B_FILE_INFO_THUMB, 'w+');
			fwrite($fp, serialize($data));
			fclose($fp);

			if($progress) {
				$response['status'] = 'progress';
				$response['progress'] = 100;
				$this->sendChunk(',' . json_encode($response));
				sleep(1);
			}
		}

		function createThumbnail_callback() {
			$this->create_nodes++;
			$response['status'] = 'progress';
			$response['progress'] = round($this->create_nodes / $this->total_nodes * 100);
			if($this->progress != $response['progress']) {
				$this->sendChunk(',' . json_encode($response));
				$this->progress = $response['progress'];
			}

		}

		function download() {
			if($this->request['download_node_id'] && $this->request['download_node_id'] != 'null') {
				foreach($this->request['download_node_id'] as $node_id) {
					$nodes[] = new B_FileNode($this->dir, $node_id, null, null, 'all');
				}
				if(count($nodes) == 1 && $nodes[0]->node_type == 'file') {
					$info = pathinfo($nodes[0]->file_name);

					// Send HTTP header for download
					header('Pragma: cache;');
					header('Cache-Control: public');

					switch(strtolower($info['extension'])) {
					case 'swf':
						header('Content-type: application/x-shockwave-flash');
						break;

					case 'css':
						header('Content-Type: text/css; charset=' . B_CHARSET);
						break;

					case 'js':
						header('Content-type: application/x-javascript');
						break;

					default:
						header('Content-Type: image/' . strtolower($info['extension']));
						break;
					}
					header('Content-Disposition: attachment; filename=' . $nodes[0]->file_name);
					ob_end_clean();
					readfile($nodes[0]->fullpath);
				}
				else {
					if(!class_exists('ZipArchive')) exit;

					$zip = new ZipArchive();
					if(count($nodes) == 1) {
						if($this->request['download_node_id'][0] == 'root') {
							$file_name = 'root.zip';
						}
						else {
							$file_name = $nodes[0]->file_name . '.zip';
						}
					}
					else {
						$file_name = 'files.zip';
					}

					$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

					if(!$zip->open($file_path, ZipArchive::CREATE)) {
						exit;
					}

					foreach($nodes as $node) {
						$node->serializeForDownload($data);
						foreach($data as $key => $value) {
							if($value) {
								$zip->addFile($value, $key);
							}
							else {
								$zip->addEmptyDir($key);
							}
						}
					}
					$zip->close();

					// Send HTTP header for download
					header('Pragma: cache;');
					header('Cache-Control: public');
					header('Content-type: application/x-zip-dummy-content-type');
					header('Content-Disposition: attachment; filename=' . $file_name);
					ob_end_clean();
					readfile($file_path);

					// Remove
					unlink($file_path);
				}
			}

			exit;
		}

		function preview() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				// Redircet to top page
				$path = B_Util::getPath(B_UPLOAD_URL, $this->request['node_id']);
				header("Location:$path");
			}

			exit;
		}

		function response($node_id, $category) {
			// If thumb-nail cache file not exists
			if(!file_exists(B_FILE_INFO_THUMB)) {
				$this->createThumbnailCacheFile();
			}
			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}

			$root_node = new B_FileNode($this->dir, '/', $this->session['open_nodes'], null, 1);
			if($this->session['sort_key']) {
				$current_node = $root_node->getNodeById($this->session['current_node']);
				if($current_node) {
					$current_node->setSortKey($this->session['sort_key']);
					$current_node->setSortOrder($this->session['sort_order']);
				}
			}

			$list[] = $root_node->getNodeList($node_id, $category);

			$response['current_node'] = $this->session['current_node'];

			if($this->session['selected_node']) {
				$response['selected_node'] = $this->session['selected_node'];
			}
			if($list) {
				$response['node_info'] = $list;
			}
			if($this->session['sort_key']) {
				$response['sort_key'] = $this->session['sort_key'];
				$response['sort_order'] = $this->session['sort_order'];
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
		}

		function getErrorMessage($error) {
			global $g_data_set, ${$g_data_set};

			return ${$g_data_set}['node_error'][$error];
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_index.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/filemanager_tree.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/filemanager.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/upload.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tree.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_dialog.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_progress_bar.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_splitter.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
