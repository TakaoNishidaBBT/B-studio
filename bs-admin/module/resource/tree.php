<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_tree extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/tree_config.php');
			$this->tree_config = $tree_config;
			$this->tree = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, null
									, null
									, 0
									, null);

			$this->tree->setConfig($this->tree_config);

			if($this->request['node_id']) {
				$this->openCurrentNode($this->request['node_id']);
			}
			if($this->request['mode'] == 'open') {
				$this->openCurrentNode($this->session['current_node']);
			}

			if(!$this->session['sort_order']) $this->session['sort_order'] = 'asc';
			if(!$this->session['sort_key']) $this->session['sort_key'] = 'node_name';

			$this->status = true;
		}

		function openCurrentNode($node_id) {
			$sql_org = "select parent_node from %VIEW% where node_id='%NODE_ID%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql_org);

			for($id = $node_id; $id && $id != 'root'; $id = $row['parent_node']) {
				$this->session['open_nodes'][$id] = true;
				$sql = str_replace('%NODE_ID%', $id, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}
		}

		function getNodeList() {
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

			header('Content-Type: application/x-javascript charset=utf-8');
			$response['status'] = true;
			echo json_encode($response);

			exit;
		}

		function pasteNode() {
			if($this->request['source_node_id'] && $this->request['source_node_id'] != 'null') {
				if($this->request['destination_node_id'] != 'trash' && $this->tree->checkDuplicateById($this->request['destination_node_id'], $this->request['source_node_id'])) {

					$this->message = __('Already exists');
					$this->status = false;
				}
				else {
					switch($this->request['mode']) {
					case 'arias':
						// start transaction
						$this->db->begin();
						foreach($this->request['source_node_id'] as $node_id) {
							if(!$node_id) continue;
							$source_node = new B_Node($this->db
													, B_RESOURCE_NODE_TABLE
													, B_WORKING_RESOURCE_NODE_VIEW
													, $this->version['working_version_id']
													, $this->version['revision_id']
													, $node_id
													, null
													, 'all'
													, null);

							$ret = $source_node->arias($this->request['destination_node_id'], $this->user_id);
						}
						break;

					case 'copy':
						// Set time limit to 5 minutes
						set_time_limit(300);

						// start transaction
						$this->db->begin();

						$this->copy_nodes = 0;
						$this->total_copy_nodes = 0;
						$ret = true;
						foreach($this->request['source_node_id'] as $node_id) {
							if(!$node_id) continue;
							$node = new B_Node($this->db
											, B_RESOURCE_NODE_TABLE
											, B_WORKING_RESOURCE_NODE_VIEW
											, $this->version['working_version_id']
											, $this->version['revision_id']
											, $node_id
											, null
											, 'all'
											, null
											, true);

							if($node->isMyChild($this->request['destination_node_id'])) {
								$node->error_no = 1;
								$ret = false;
								break;
							}

							$this->total_copy_nodes += $node->nodeCount();
							$source_node[] = $node;
						}
						if(!$ret) break;

						if($this->total_copy_nodes >= 40) {
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
						}

						foreach($source_node as $node) {
							$ret = $node->copy($this->request['destination_node_id'], $this->user_id, $new_node_id, array('obj' => $this, 'method' => 'copy_callback'));
							if(!$ret) break;
							$this->selected_node[] = $new_node_id[0];
						}

						break;

					case 'cut':
						// start transaction
						$this->db->begin();
						foreach($this->request['source_node_id'] as $node_id) {
							if(!$node_id) continue;
							$source_node = new B_Node($this->db
													, B_RESOURCE_NODE_TABLE
													, B_WORKING_RESOURCE_NODE_VIEW
													, $this->version['working_version_id']
													, $this->version['revision_id']
													, $node_id
													, null
													, 0
													, null);

							$ret = $source_node->move($this->request['destination_node_id'], $this->user_id);
							if(!$ret) break;
						}
						break;
					}
					if($ret) {
						$this->db->commit();
						$this->status = true;
						$this->session['open_nodes'][$this->request['destination_node_id']] = true;

						// kick refresh-cache process
						$this->refreshCache();
					}
					else {
						$this->db->rollback();
						$this->status = false;
						if(!$node) $node = $source_node ? $source_node : $destination_node;
						$this->message = $this->getErrorMessage($node->getErrorNo());

						if($this->show_progress) {
							$response['status'] = 'error';
							$response['message'] = $this->getErrorMessage($node->getErrorNo());
							$this->sendChunk(',' . json_encode($response));
							sleep(1);
						}
					}
				}
			}
			if($this->show_progress) {
				$response['status'] = 'finished';
				$response['progress'] = 100;
				$this->sendChunk(',' . json_encode($response));
				$this->sendChunk();	// terminate
			}

			if(!$response) $this->response($this->request['node_id'], 'select');
			exit;
		}

		function copy_callback(&$node) {
			$this->copy_nodes++;

			if($node->node_type == 'file') {
				$tbl_node = new B_Table($this->db, B_RESOURCE_NODE_TABLE);

				// new_contents_id
				$new_contents_id = $tbl_node->selectMaxValuePlusOne('node_id');
				$node->new_contents_id = $new_contents_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];

				// copy
				$file = pathinfo($node->node_name);
				$file_name = B_RESOURCE_DIR . $node->contents_id . '.' . $file['extension'];
				$new_file_name = B_RESOURCE_DIR . $node->new_contents_id . '.' . $file['extension'];
				copy($file_name, $new_file_name);

				// copy thumbnail
				switch(strtolower($file['extension'])) {
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png':
				case 'bmp':
					$thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $node->contents_id . '.' . $file['extension'];
					$new_thumb_file_name = B_RESOURCE_DIR . 'thumb_' . $node->new_contents_id . '.' . $file['extension'];
					copy($thumb_file_name, $new_thumb_file_name);
					break;
				}
			}

			if($this->show_progress) {
				$response['status'] = 'progress';
				$response['progress'] = round($this->copy_nodes / $this->total_copy_nodes * 100);
				if($this->progress != $response['progress']) {
					$this->sendChunk(',' . json_encode($response));
					$this->progress = $response['progress'];
				}
			}
			return true;
		}

		function createNode() {
			$this->session['open_nodes'][$this->request['destination_node_id']] = true;
			$node = new B_Node($this->db
							, B_RESOURCE_NODE_TABLE
							, B_WORKING_RESOURCE_NODE_VIEW
							, $this->version['working_version_id']
							, $this->version['revision_id']
							, $this->request['destination_node_id']
							, null
							, 1
							, null);

			$node->setConfig($this->tree_config);

			// start transaction
			$this->db->begin();
			$ret = $node->insert($this->request['node_type'], $this->request['node_class'], $this->user_id, $new_node_id, $new_node_name);
			if($ret) {
				$this->status = true;
				$this->db->commit();
				if($this->request['node_type'] == 'file') {
					// set contents_id
					$new_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $new_node_id
									, null
									, 1
									, null);

					$contents_id = $new_node_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
					$ret = $new_node->setContentsId($contents_id, $this->user_id);

					$file_info = pathinfo($new_node_name);
					$file_name = B_RESOURCE_DIR . $new_node_id . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'] . '.' . $file_info['extension'];
					$fp = fopen($file_name, 'w');
					fclose($fp);
				}
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message = $this->getErrorMessage($node->getErrorNo());
			}
			$this->response($new_node_id, 'new_node');
			exit;
		}

		function deleteNode() {
			if($this->request['delete_node_id'] && $this->request['delete_node_id'] != 'null') {
				// start transaction
				$this->db->begin();

				$disp_seq = 0;
				foreach($this->request['delete_node_id'] as $node_id) {
					if(!$node_id) continue;

					$source_node = new B_Node($this->db
											, B_RESOURCE_NODE_TABLE
											, B_WORKING_RESOURCE_NODE_VIEW
											, $this->version['working_version_id']
											, $this->version['revision_id']
											, $node_id
											, null
											, 0
											, null);

					$parent = $source_node->parent;
					$ret = $source_node->move('trash', $this->user_id, $disp_seq);
					if($ret) {
						$disp_seq++;
						if($source_node->isMyChild($this->session['current_node'])) {
							$this->session['current_node'] = $parent;
						}
					}
					else {
						break;
					}
				}
				if($ret) {
					$this->status = true;
					$this->db->commit();

					// kick refresh-cache process
					$this->refreshCache();
				}
				else {
					$this->status = false;
					$this->db->rollback();
					$this->message = $this->getErrorMessage($source_node->getErrorNo());
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function truncateNode() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				ignore_user_abort(true);

				// set time limit to 5 minutes
				set_time_limit(300);

				// start transaction
				$this->db->begin();

				$node = new B_Node($this->db
								, B_RESOURCE_NODE_TABLE
								, B_WORKING_RESOURCE_NODE_VIEW
								, $this->version['working_version_id']
								, $this->version['revision_id']
								, $this->request['node_id']
								, null
								, 'all'
								, null);

				$this->total_truncate_nodes = $node->nodeCount();
				if($this->total_truncate_nodes >= 500) {
					// send progress
					header('Content-Type: application/octet-stream');
					header('Transfer-encoding: chunked');
					flush();
					ob_flush();

					// Send start message
					$response['status'] = 'show';
					$response['message'] = 'Processing ...';
					$response['progress'] = 0;
					$this->progress = 0;
					$this->sendChunk(json_encode($response));

					$this->show_progress = true;
					sleep(1);
				}

				$ret = $node->delete(array('obj' => $this, 'method' => 'truncate_callback'));
				if($ret) {
					if($this->show_progress) {
						usleep(500000);

						$response['status'] = 'message';
						$response['message'] = 'Clean up Files';
						$this->sendChunk(',' . json_encode($response));

						// delete useless files
						$this->truncateFiles();
						sleep(1);

						$response['status'] = 'message';
						$response['message'] = 'Clean up DB';
						$this->sendChunk(',' . json_encode($response));

						// clean up DB
						$ret = $this->cleanUpDB();
						sleep(1);
					}
					else {
						// delete useless files
						$this->truncateFiles();

						// clean up DB
						$ret = $this->cleanUpDB();
					}
				}
				if($ret) {
					$this->status = true;
					$this->db->commit();

					// kick refresh-cache process
					$this->refreshCache();
				}
				else {
					$this->status = false;
					$this->db->rollback();
					$this->message = $this->getErrorMessage($node->getErrorNo());
				}
			}
			if($this->show_progress) {
				$response['status'] = 'finished';
				$response['progress'] = 100;
				$this->sendChunk(',' . json_encode($response));
				$this->sendChunk();	// terminate
			}
			else {
				$this->response($this->request['node_id'], 'select');
			}
			exit;
		}

		function truncate_callback(&$node) {
			$this->truncate_nodes++;
			if($this->show_progress) {
				$response['status'] = 'progress';
				$response['progress'] = round($this->truncate_nodes / $this->total_truncate_nodes * 100);
				if($this->progress != $response['progress']) {
					$this->sendChunk(',' . json_encode($response));
					$this->progress = $response['progress'];
				}
			}
			return true;
		}

		function truncateFiles() {
			$sql = "select contents_id, count(*) cnt, max(del_flag) del_flag, max(node_name) node_name
					from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
					where node_type = 'file'
					group by contents_id
					having cnt = 1 and del_flag = '1'";

			$rs = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($rs)) {
				$info = pathinfo($row['node_name']);
				$file_name = B_RESOURCE_DIR . $row['contents_id'] . '.' . strtolower($info['extension']);
				switch($info['extension']) {
				case 'avi':
				case 'flv':
				case 'mp4':
				case 'mpg':
				case 'mpeg':
				case 'wmv':
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.jpg';
					break;

				default:
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.' . strtolower($info['extension']);
					break;
				}
				$truncate_files++;

				if(file_exists($file_name)) {
					unlink($file_name);
				}
				if(file_exists($thumb_file_name)) {
					unlink($thumb_file_name);
				}
			}
		}

		function cleanUpDB() {
			// delete useless record
			$sql = "delete from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
					where concat(version_id, revision_id, node_id) in
					(
						select id from (
							select concat(max(version_id), max(revision_id), node_id) id
								   , max(del_flag) del_flag
							from " . B_DB_PREFIX . B_RESOURCE_NODE_TABLE . "
							 group by node_id
							 having count(*) = 1
						) as tmp
						where del_flag = '1'
					)";

			$ret = $this->db->query($sql);
			return $ret;
		}

		function saveName() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node_name = trim($this->request['node_name']);
				if($this->checkFileName($this->request['node_id'], $node_name)) {
					$node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['node_id']
									, null
									, 0
									, null);

					$old_name = $node->node_name;

					// start transaction
					$this->db->begin();

					$ret = $node->saveName($node_name, $this->user_id);

					$contents_id = $this->request['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];
					$request_info = pathinfo($node_name);
					$old_name_info = pathinfo($old_name);

					if($ret) {
						if($request_info['extension'] != $old_name_info['extension']) {
							if($node->contents_id == $contents_id) {
								// change extension
								$ret = rename(B_RESOURCE_DIR . $node->contents_id . '.' . $old_name_info['extension'],
												B_RESOURCE_DIR . $node->contents_id . '.' . $request_info['extension']);
								$thumbnail = B_RESOURCE_DIR . B_THUMB_PREFIX . $node->contents_id . '.' . $old_name_info['extension'];
								if(file_exists($thumbnail)) {
									$ret = rename($thumbnail, B_RESOURCE_DIR . B_THUMB_PREFIX . $node->contents_id . '.' . $request_info['extension']);
								}
							}
							else {
								$old_contents_id = $node->contents_id;
								$ret = $node->setContentsId($contents_id, $this->user_id);
								// change extension
								$ret = copy(B_RESOURCE_DIR . $old_contents_id . '.' . $old_name_info['extension'],
												B_RESOURCE_DIR . $contents_id . '.' . $request_info['extension']);

								$thumbnail = B_RESOURCE_DIR . B_THUMB_PREFIX . $old_contents_id . '.' . $old_name_info['extension'];
								if(file_exists($thumbnail)) {
									$ret = copy($thumbnail, B_RESOURCE_DIR . B_THUMB_PREFIX . $contents_id . '.' . $request_info['extension']);
								}
							}
						}
					}
					if($ret) {
						$this->status = true;
						$this->db->commit();

						// kick refresh-cache process
						$this->refreshCache();
					}
					else {
						$this->status = false;
						$this->db->rollback();
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function checkFileName($node_id, $file_name) {
			if(preg_match('/[\\\\:\/\*\?\"\'<>\|\s]/', $file_name)) {
				$this->message = __('The following charcters cannot be used in file or folder names (\ / : * ? " \' < > | space)');
				return false;
			}
			if(strlen($file_name) != mb_strlen($file_name)) {
				$this->message = __('Multi-byte characters cannot be used');
				return false;
			}
			if(substr($file_name, -1) == '.') {
				$this->message = __('Please enter the file extension');
				return false;
			}

			$node_type = $this->tree->getNodeTypeById($node_id);

			if(!strlen(trim($file_name))) {
				$this->message = __('Please enter a name for the %ITEM%');
				$this->message = str_replace('%ITEM%', __($node_type), $this->message);
				return false;
			}
			if($this->tree->checkDuplicateByName($node_id, $file_name)) {
				$this->message = __('A %ITEM% with this name already exists. Please enter a different name.');
				$this->message = str_replace('%ITEM%', __($node_type), $this->message);
				return false;
			}
			return true;
		}

		function updateDispSeq() {
			if($this->request['parent_node_id'] && $this->request['parent_node_id'] != 'null') {
				if($this->tree->checkDuplicateById($this->request['parent_node_id'], $this->request['source_node_id'])) {
					$this->message = __('Already exists');
					$this->status = false;
				}
				else {
					$node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $this->request['parent_node_id']
									, null
									, 1
									, null);

					// start transaction
					$this->db->begin();
					$ret = $node->updateDispSeq($this->request, $this->user_id);
					if($ret) {
						$this->status = true;
						$this->db->commit();

						// kick refresh-cache process
						$this->refreshCache();
					}
					else {
						$this->status = false;
						$this->db->rollback();
						$this->message = $this->getErrorMessage($node->getErrorNo());
					}
				}
			}
			$this->response($this->request['node_id'], 'select');
			exit;
		}

		function download() {
			if($this->request['mode'] == 'download') {
				$this->downloadFile($this->request['file_name'], $this->request['file_path'], $this->request['remove']);
			}
			else {
				$this->createFile();
			}
		}

		function createFile() {
			if($this->request['download_node_id'] && $this->request['download_node_id'] != 'null') {
				foreach($this->request['download_node_id'] as $node_id) {
					$nodes[] = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, $node_id
									, null
									, 'all'
									, null);
				}
				if(count($nodes) == 1 && $nodes[0]->node_type == 'file') {
					$info = pathinfo($nodes[0]->node_name);
					$file_path = B_RESOURCE_DIR . $nodes[0]->contents_id . '.' . $info['extension'];

					// finish
					$response['status'] = 'download';
					$response['remove'] = false;
					$response['file_name'] = $nodes[0]->node_name;
					$response['file_path'] = $file_path;
					header('Content-Type: application/x-javascript charset=utf-8');
					echo json_encode($response);
				}
				else {
					if(!class_exists('ZipArchive')) exit;

					ignore_user_abort(true);

					// set time limit to 5 minutes
					set_time_limit(300);

					// send progress
					header('Content-Type: application/octet-stream');
					header('Transfer-encoding: chunked');
					flush();
					ob_flush();

					// Send start message
					$response['status'] = 'show';
					$response['progress'] = 0;
					$response['message'] = 'Creating zip file ';
					$progress = 0;
					$this->sendChunk(json_encode($response));

					if(count($nodes) == 1) {
						if($this->request['download_node_id'][0] == 'root') {
							$file_name = 'root.zip';
						}
						else {
							$file_name = $nodes[0]->node_name . '.zip';
						}
					}
					else {
						$file_name = 'resources.zip';
					}

					$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

					$cmdline = 'php ' . B_DOC_ROOT . B_ADMIN_ROOT . 'module/resource/archive.php';
					$cmdline .= ' ' . $_SERVER['SERVER_NAME'];
					$cmdline .= ' ' . $_SERVER['DOCUMENT_ROOT'];
					$cmdline .= ' ' . $this->version['working_version_id'];
					$cmdline .= ' ' . $this->version['revision_id'];
					$cmdline .= ' ' . $file_path;

					$escape = '"';
					foreach($this->request['download_node_id'] as $node_id) {
						$cmdline .= ' ' . $escape . $node_id . $escape;
					}

					// kick as a background process
					B_Util::fork($cmdline);

					for($total_file_size=0, $i=0; $i<count($nodes); $i++) {
						$total_file_size+= $nodes[$i]->fileSize();
					}

					// send progress 
					for($cnt=0 ;; $cnt++) {
						usleep(40000);
						if(file_exists($file_path)) {
							$response['status'] = 'progress';
							$response['progress'] = 100;
							$this->sendChunk(',' . json_encode($response));
							usleep(300000);

							$response['status'] = 'complete';
							$response['progress'] = 100;
							$response['message'] = 'Complete !';
							$this->sendChunk(',' . json_encode($response));
							sleep(1);
							break;
						}

						if($cnt%4 == 0) {
							unset($dots);
							for($i=0; $i<($cnt/4%8); $i++) {
								$dots.= '.';
							}
							$response['status'] = 'message';
							$response['message'] = "Creating zip file {$dots}";

							$this->sendChunk(',' . json_encode($response));
						}

						usleep(40000);

						$response['status'] = 'progress';
						$response['progress'] = round($cnt / $total_file_size * 100 * 1000000);
						if($response['progress'] > 99) $response['progress'] = 99;

						if($progress != $response['progress']) {
							$this->sendChunk(',' . json_encode($response));
							$progress = $response['progress'];
						}
					}

					// finish
					$response['status'] = 'download';
					$response['remove'] = true;
					$response['file_name'] = $file_name;
					$response['file_path'] = $file_path;
					$this->sendChunk(',' . json_encode($response));
					$this->sendChunk();	// terminate
					if(connection_status()) {
						unlink($file_path);
					}
				}
			}
			exit;
		}

		function downloadFile($file_name, $file_path, $remove) {
			// Download
			header('Pragma: cache;');
			header('Cache-Control: public');

			$info = pathinfo($file_name);
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

				case 'zip':
				header('Content-type: application/x-zip-dummy-content-type');

			default:
				header('Content-Type: image/' . strtolower($info['extension']));
				break;
			}

			header('Content-Disposition: attachment; filename=' . $file_name);

			ob_end_clean();
			readfile($file_path);
			if($remove === 'true') unlink($file_path);

			exit;
		}

		function preview() {
			if($this->request['node_id'] && $this->request['node_id'] != 'null') {
				$node = new B_Node($this->db
								, B_RESOURCE_NODE_TABLE
								, B_WORKING_RESOURCE_NODE_VIEW
								, $this->version['working_version_id']
								, $this->version['revision_id']
								, $this->request['node_id']
								, null
								, 0
								, null);

				if($node->node_type != 'file') exit;

				$path = $node->getPath();

				// Redirect to index.php
				$path = B_SITE_BASE . $path;
				header("Location:$path");
			}

			exit;
		}

		function response($node_id, $category) {
			$response['status'] = $this->status;
			if($this->message) {
				$response['message'] = $this->message;
			}

			$root_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 1
									, $this->session['open_nodes']
									, false
									, 'auto');

			if($this->session['sort_key']) {
				$current_node = $root_node->getNodeById($this->session['current_node']);
				if($current_node) {
					$current_node->setSortKey($this->session['sort_key']);
					$current_node->setSortOrder($this->session['sort_order']);
				}
			}

			$list[] = $root_node->getNodeList($node_id, $category, B_RESOURCE_DIR);
			$trash_node = new B_Node($this->db
									, B_RESOURCE_NODE_TABLE
									, B_WORKING_RESOURCE_NODE_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'trash'
									, null
									, 0
									, $this->session['open_nodes']
									, false
									, 'auto');

			if($this->session['sort_key']) {
				$current_node = $trash_node->getNodeById($this->session['current_node']);
				if($current_node) {
					$current_node->setSortKey($this->session['sort_key']);
					$current_node->setSortOrder($this->session['sort_order']);
				}
			}

			$list[] = $trash_node->getNodeList('', '', B_RESOURCE_DIR);

			$response['current_node'] = $this->session['current_node'];
			if($this->selected_node) {
				$response['selected_node'] = $this->selected_node;
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

			$this->html_header->appendProperty('css', '<link href="css/resource.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/resource_tree.css" type="text/css" rel="stylesheet" media="all" />');
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
