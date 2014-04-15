<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class widget_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);

			$this->tab_control = new B_Element($tab_control_config);

			$this->contents_table = new B_Table($this->db, B_WIDGET_TABLE);

			$this->status = true;
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if($this->request['node_id']) {
				$this->node_info = $this->getNodeInfo($this->request['node_id']);

				if($this->node_info['node_type'] != 'widget') {
					$this->setView('view_folder');
					return;
				}

				$this->_select($this->node_info);
			}
		}

		function getNodeInfo($node_id) {
			$sql = "select * from %VIEW% where node_id='%NODE_ID%'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_WIDGET_NODE_VIEW, $sql);
			$sql = str_replace('%NODE_ID%', $node_id, $sql);
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function _select($node_info) {
			if($node_info['contents_id']) {
				$sql = "select * from %VIEW% where contents_id='%CONTENTS_ID%'";
				$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_WIDGET_VIEW, $sql);
				$sql = str_replace('%CONTENTS_ID%', $node_info['contents_id'], $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}

			// DBから値を設定
			$row['node_id'] = $node_info['node_id'];
			$this->form->setValue($row);
		}

		function truncate() {
			$this->setView('view_folder');
		}

		function regist() {
			$this->form->setValue($this->post);

			if($this->post['contents_id']) {
				$contents_id = $this->post['contents_id'];

				$sql = "select * from %WIDGET_TABLE%
						where version_id = '%VERSION_ID%'
						and revision_id = '%REVISION_ID%'
						and contents_id='$contents_id'";

				$sql = str_replace('%WIDGET_TABLE%', B_DB_PREFIX . B_WIDGET_TABLE, $sql);
				$sql = str_replace('%VERSION_ID%', $this->version['working_version_id'], $sql);
				$sql = str_replace('%REVISION_ID%', $this->version['revision_id'], $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				if($row) {
					if($this->post['mode'] == 'confirm' && $row['update_datetime'] > $this->post['update_datetime']) {
						$this->status = true;
						$this->mode = 'confirm';
						$this->message = "他のユーザに更新されています。\n上書きしますか？";
					}
					else {
						$this->update($this->user_id, $contents_id);
					}
				}
				else {
					$this->insert($this->user_id, $contents_id);
				}
			}
			else {
				$this->selectInsert($this->user_id, $contents_id);
			}

			$response['status'] = $this->status;
			$response['mode'] = $this->mode;
			$response['message_obj'] = 'message';
			$response['message'] = $this->message;
			if($this->status && $this->mode != 'confirm') {
				$response['values'] = array('contents_id' => $contents_id, 'update_datetime' => time());
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo $this->util->json_encode($response);
			exit;
		}

		function update($user_id, $contents_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($contents_data);

			$contents_data['contents_id'] = $contents_id;
			$contents_data['update_user'] = $user_id;
			$contents_data['update_datetime'] = time();
			$contents_data['version_id'] = $this->version['working_version_id'];
			$contents_data['revision_id'] = $this->version['revision_id'];

			$ret = $this->contents_table->update($contents_data);

			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = "登録しました";
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message = "登録に失敗しました";
			}
		}

		function insert($user_id, $contents_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($contents_data);

			$contents_data['create_user'] = $user_id;
			$contents_data['create_datetime'] = time();
			$contents_data['update_user'] = $user_id;
			$contents_data['update_datetime'] = time();
			$contents_data['del_flag'] = '0';
			$contents_data['version_id'] = $this->version['working_version_id'];
			$contents_data['revision_id'] = $this->version['revision_id'];
			$contents_data['contents_id'] = $contents_id;

			$ret = $this->contents_table->insert($contents_data);
			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = "登録しました";
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message = "登録に失敗しました";
			}
		}

		function selectInsert($user_id, &$contents_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($contents_data);

			$contents_data['create_user'] = $user_id;
			$contents_data['create_datetime'] = time();
			$contents_data['update_user'] = $user_id;
			$contents_data['update_datetime'] = time();
			$contents_data['del_flag'] = '0';
			$contents_data['version_id'] = $this->version['working_version_id'];
			$contents_data['revision_id'] = $this->version['revision_id'];

			$ret = $this->contents_table->selectInsert($contents_data);
			$contents_id = $this->contents_table->selectMaxValue('contents_id');

			if($ret) {
				$this->node = new B_Node($this->db
										, B_WIDGET_NODE_TABLE
										, B_WORKING_WIDGET_NODE_VIEW
										, $this->version['working_version_id']
										, $this->version['revision_id']
										, $contents_data['node_id']
										, null
										, 1
										, null);

				$ret = $this->node->setContentsId($contents_id, $user_id);
			}

			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = "登録しました";
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message = "登録に失敗しました";
			}
		}

		function view() {

			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/widget_form.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js"type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_preview.js" type="text/javascript"></script>');
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

			require_once('./view/view_form.php');
		}

		function view_folder() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/widget_form.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			echo '<body></body>';
		}
	}
