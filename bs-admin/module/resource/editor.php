<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
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

				$info = B_Util::pathinfo($row['node_name']);
				$file_path = B_RESOURCE_DIR . $row['contents_id'] . '.' . $info['extension'];
				$update_datetime = filemtime($file_path);
				$contents = file_get_contents($file_path);
				$encoding = mb_detect_encoding($contents, 'auto');
				$obj = $this->editor->getElementByName('contents');
				$obj->value = mb_convert_encoding($contents, 'UTF-8', 'auto');
				switch(strtolower($info['extension'])) {
				case 'js':
					$obj->special_html = str_replace('%SYNTAX%', 'syntax="javascript"', $obj->special_html);
					break;

				case 'css':
					$obj->special_html = str_replace('%SYNTAX%', 'syntax="css"', $obj->special_html);
					break;

				default:
					$obj->special_html = str_replace('%SYNTAX%', '', $obj->special_html);
					break;
				}

				$obj = $this->editor->getElementByName('node_id');
				$obj->value = $row['node_id'];

				$obj = $this->editor->getElementByName('contents_id');
				$obj->value = $row['contents_id'];

				$obj = $this->editor->getElementByName('extension');
				$obj->value = $info['extension'];

				$obj = $this->tab_control->getElementByName('encoding');
				$obj->value = $encoding;

				$obj = $this->editor->getElementByName('update_datetime');
				$obj->value = $update_datetime;

				$obj = $this->tab_control->getElementByName('text_editor_index');
				$obj->value = B_SITE_ROOT . $this->getFilePath($this->request['node_id']);

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

		function regist() {
			$ret = $this->updateNode($contents_id);
			if($ret) {
				$this->update($contents_id, $mode, $message);
			}

			$response['status'] = true;
			$response['mode'] = $mode;
			$response['message_obj'] = 'message';
			$response['message'] = $message;
			if($mode != 'confirm') {
				$response['values'] = array('update_datetime' => time());
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
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

				// start transaction
				$this->db->begin();

				$ret = $node->setContentsId($contents_id, $this->user_id);
				if($ret) {
					$this->db->commit();
					$this->createCacheFile(B_FILE_INFO_W, B_FILE_INFO_SEMAPHORE_W, B_WORKING_RESOURCE_NODE_VIEW);
				}
				if(!$ret) {
					$this->db->rollback();
					$message = "登録に失敗しました";
				}
			}

			return $ret;
		}

		function update($contents_id, &$mode, &$message) {
			$file_path = B_RESOURCE_DIR . $contents_id . '.' . $this->post['extension'];
			if(file_exists($file_path) && $this->post['mode'] == 'confirm' && filemtime($file_path) > $this->post['update_datetime']) {
				$mode = 'confirm';
				$message = "他のユーザに更新されています。\n上書きしますか？";
			}
			else {
				if($this->post['encoding'] == 'ASCII' || $this->post['encoding'] == 'UTF-8') {
					$contents = $this->post['contents'];
				}
				else {
					$contents = mb_convert_encoding($this->post['contents'], $this->post['encoding'], 'auto');
				}
				file_put_contents($file_path, $contents, LOCK_EX);

				// cache 更新
				if(file_exists(B_FILE_INFO_W)) {
					touch(B_FILE_INFO_W);
				}

				// 同一バージョン
				if($this->version['current_version'] == $this->version['working_version'] && file_exists(B_FILE_INFO_C)) {
					touch(B_FILE_INFO_C);
				}

				$message = "登録しました";
			}
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/editor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_texteditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ace.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/ext-split.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/theme-twilight.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-html.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-css.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ace/mode-php.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_editor.php');
		}
	}
