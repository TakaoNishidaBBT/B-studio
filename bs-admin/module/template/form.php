<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class template_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);
			$this->config_form = new B_Element($config_form_config);

			$obj = $this->form->getElementByName('config_form');
			$obj->addElement($this->config_form);

			$this->tab_control = new B_Element($tab_control_config);

			$this->contents_table = new B_Table($this->db, B_TEMPLATE_TABLE);

			$this->status = true;
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if($this->request['node_id']) {
				$this->node_info = $this->getNodeInfo($this->request['node_id']);

				if($this->node_info['node_type'] != 'template') {
					$this->setView('view_folder');
					return;
				}

				$this->_select($this->node_info);
			}
		}

		function getNodeInfo($node_id) {
			$sql = "select * from %VIEW% where node_id='$node_id'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_TEMPLATE_NODE_VIEW, $sql);
			$rs = $this->db->query($sql);
			return $this->db->fetch_assoc($rs);
		}

		function _select($node_info) {
			if($node_info['contents_id']) {
				$sql = "select * from %VIEW% where contents_id='%CONTENTS_ID%'";
				$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_TEMPLATE_VIEW, $sql);
				$sql = str_replace('%CONTENTS_ID%', $node_info['contents_id'], $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}

			// Set value from DB
			$row['node_id'] = $node_info['node_id'];
			$this->form->setValue($row);
		}

		function truncate() {
			$this->setView('view_folder');
		}

		function register() {
			$this->form->setValue($this->post);

			if($this->post['contents_id']) {
				$contents_id = $this->post['contents_id'];

				$sql = "select * from %TEMPLATE_TABLE%
						where version_id = '%VERSION_ID%'
						and revision_id = '%REVISION_ID%'
						and contents_id='$contents_id'";

				$sql = str_replace('%TEMPLATE_TABLE%', B_DB_PREFIX . B_TEMPLATE_TABLE, $sql);
				$sql = str_replace('%VERSION_ID%', $this->version['working_version_id'], $sql);
				$sql = str_replace('%REVISION_ID%', $this->version['revision_id'], $sql);
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				if($row) {
					if($this->post['mode'] == 'confirm' && $row['update_datetime'] > $this->post['update_datetime']) {
						$this->status = true;
						$this->mode = 'confirm';
						$this->message = __("Another user has updated this record\nAre you sure you want to overwrite?");
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
			echo json_encode($response);
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
				$this->message = __('Saved');
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
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
				$this->message = __('Saved');
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
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
										, B_TEMPLATE_NODE_TABLE
										, B_WORKING_TEMPLATE_NODE_VIEW
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
				$this->message = __('Saved');
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
			}
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template_form.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/texteditor.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_preview.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_effect.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_texteditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_shortcut.js" type="text/javascript"></script>');
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

		function view_folder() {
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/template_form.css" type="text/css" rel="stylesheet" media="all" />');

			$this->showHtmlHeader();

			echo '<body></body>';
		}
	}
