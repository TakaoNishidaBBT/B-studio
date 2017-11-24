<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class resource_editor extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/editor_config.php');

			$this->editor = new B_Element($editor_config);
			$this->tab_control = new B_Element($tab_control_config);
		}

		function open() {
			if($this->request['node_id']) {
				$id = $this->db->real_escape_string($this->request['node_id']);
				$sql = "select * from %VIEW% where node_id='%NODE_ID%'";
				$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql);
				$sql = str_replace('%NODE_ID%', $id, $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				$info = pathinfo($row['node_name']);
				$file_path = B_RESOURCE_DIR . $row['contents_id'] . '.' . $info['extension'];
				$update_datetime = filemtime($file_path);
				$obj = $this->editor->getElementByName('contents');
				$contents = file_get_contents($file_path);
				if($contents) {
					$encoding = mb_detect_encoding($contents);
					$obj->value = mb_convert_encoding($contents, 'UTF-8', B_MB_DETECT_ORDER);
				}

				switch(strtolower($info['extension'])) {
				case 'js':
					$obj->special_html = str_replace('%SYNTAX%', 'data-syntax="javascript"', $obj->special_html);
					break;

				case 'css':
					$obj->special_html = str_replace('%SYNTAX%', 'data-syntax="css"', $obj->special_html);
					break;

				default:
					$obj->special_html = str_replace('%SYNTAX%', '', $obj->special_html);
					break;
				}

				$obj = $this->editor->getElementByName('node_id');
				if($obj) $obj->value = $row['node_id'];

				$obj = $this->editor->getElementByName('contents_id');
				if($obj) $obj->value = $row['contents_id'];

				$obj = $this->editor->getElementByName('extension');
				if($obj) $obj->value = $info['extension'];

				$obj = $this->tab_control->getElementByName('encoding');
				if($obj) {
					$obj->data_set_value = B_Util::get_mb_detect_order();
					$obj->value = $encoding;
				}

				$obj = $this->editor->getElementByName('update_datetime');
				if($obj) $obj->value = $update_datetime;

				$obj = $this->tab_control->getElementByName('text_editor_index');
				if($obj) $obj->value = B_SITE_BASE . $this->getFilePath($this->request['node_id']);

				$this->setTitle($row['node_name']);
			}
		}

		function getFilePath($node_id) {
			$sql_org = "select node_name, parent_node from %VIEW% where node_id='%NODE_ID%'";
			$sql_org = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_RESOURCE_NODE_VIEW, $sql_org);

			for($id = $node_id; $id && $id != 'root'; $id = $row['parent_node']) {

				$sql = str_replace('%NODE_ID%', $id, $sql_org);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
				if($path) $path = '/' . $path;
				if($id == 'trash') {
					$path = 'trash box' . $path;
				}
				else {
					$path = $row['node_name'] . $path;
				}
			}
			return $path;
		}

		function register() {
			// start transaction
			$this->db->begin();

			$ret = $this->updateNode($contents_id);
			if($ret) {
				$ret = $this->update($contents_id, $mode, $message);
			}
			if($ret) {
				$this->db->commit();
				$this->refreshCache();
			}
			if(!$ret) {
				$this->db->rollback();
				$message = __('Failed to save');
			}

			$response['status'] = true;
			$response['mode'] = $mode;
			$response['message_obj'] = 'message';
			$response['message'] = $message;
			if($mode != 'confirm') {
				$response['values'] = array('update_datetime' => time());
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
			exit;
		}

		function updateNode(&$contents_id) {
			$ret = true;
			$contents_id = $this->post['node_id'] . '_' . $this->version['working_version_id'] . '_' . $this->version['revision_id'];

			if($this->post['contents_id'] != $contents_id) {
				$node = new B_Node($this->db
								, B_RESOURCE_NODE_TABLE
								, B_WORKING_RESOURCE_NODE_VIEW
								, $this->version['working_version_id']
								, $this->version['revision_id']
								, $this->post['node_id']
								, null
								, 1
								, null);

				$ret = $node->setContentsId($contents_id, $this->user_id);
			}

			return $ret;
		}

		function update($contents_id, &$mode, &$message) {
			$ret = true;

			$filepath = B_RESOURCE_DIR . $contents_id . '.' . $this->post['extension'];
			if(file_exists($filepath) && $this->post['mode'] == 'confirm' && filemtime($filepath) > $this->post['update_datetime']) {
				$mode = 'confirm';
				$message = __("Another user has updated this file\nAre you sure you want to overwrite?");
			}
			else {
				if($this->post['encoding'] == 'ASCII' || $this->post['encoding'] == 'UTF-8') {
					$contents = $this->post['contents'];
				}
				else {
					$contents = mb_convert_encoding($this->post['contents'], $this->post['encoding'], 'UTF-8');
				}

				// Get file size
				$filesize = file_put_contents($filepath, $contents, LOCK_EX);

				$param['node_id'] = $this->post['node_id'];
				$param['version_id'] = $this->version['working_version_id'];
				$param['revision_id'] = $this->version['revision_id'];
				$param['update_datetime'] = time();

				// Set file size
				$param['file_size'] = $filesize;
				$param['human_file_size'] = B_Util::human_filesize($param['file_size'], 'K');
				$size = B_Util::getimagesize($filepath);
				if($size) {
					$param['image_size'] = $size[0] * $size[1];
					$param['human_image_size'] = $size[0] . 'x' . $size[1];
				}
				else {
					$param['image_size'] = '';
					$param['human_image_size'] = '';
				}

				$resource_node_table = new B_Table($this->db, B_RESOURCE_NODE_TABLE);
				$ret = $resource_node_table->update($param);

				// Avoid 304 Not Modified for working version
				if(file_exists(B_FILE_INFO_W)) {
					touch(B_FILE_INFO_W);
				}

				// If the working version same as the current version, touch published cache file for avoid 304 Not Modified 
				if($this->version['current_version'] == $this->version['working_version'] && file_exists(B_FILE_INFO_C)) {
					touch(B_FILE_INFO_C);
				}

				$message = __('Saved');
			}

			return $ret;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_editor.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/editor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_texteditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ace.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ext-split.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/theme-twilight.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-html.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-css.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-php.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
