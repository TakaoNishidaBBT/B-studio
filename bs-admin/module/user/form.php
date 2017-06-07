<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class user_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->mode = $this->request['mode'];

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config, $this->user_auth, $this->mode);
			$this->input_control_config = $input_control_config;
			$this->delete_control_config = $delete_control_config;
			$this->confirm_control_config = $confirm_control_config;
			$this->result_control_config = $result_control_config;
			$this->result_config = $result_config;

			$this->table_name = 'user';
			$this->table = new B_Table($this->db, $this->table_name);

			// Set mode to HTML
			$obj = $this->form->getElementByName('mode');
			$obj->setValue($this->request);
		}

		function select() {
			switch($this->mode) {
			case 'insert':
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				break;

			case 'update':
				$param['id'] = $this->request['id'];
				$row = $this->table->selectByPk($param);
				$this->form->setValue($row);
				$this->session['init_value'] = $row;

				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				break;

			case 'delete':
				$param['id'] = $this->request['id'];
				$row = $this->table->selectByPk($param);
				$this->form->setValue($row);
				$this->session['post'] = $row;
				$this->display_mode = 'confirm';

				$this->control = new B_Element($this->delete_control_config, $this->user_auth);
				break;
			}
			$this->form->setFilterValue('form');
		}

		function confirm() {
			$this->form->setValue($this->post);

			if(!$this->checkAlt($this->post)) {
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				return;
			}

			if(!$this->form->validate()) {
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				return;
			}

			$this->form->getValue($post_value);
			$this->session['post'] = $post_value;
			$this->control = new B_Element($this->confirm_control_config, $this->user_auth);

			// Set display mode
			$this->display_mode = 'confirm';
		}

		function _validate_callback($param) {
			// Check user id already exists
			$sql = "select count(*) cnt from " . B_DB_PREFIX . $this->table_name . " where user_id = binary '" . $param['value'] . "'";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			if($row['cnt'] == 0) {
				return true;
			}
			return false;
		}

		function _validate_callback2($param) {
			// Check the user id in built-in user
			global $g_auth_users;
			foreach($g_auth_users as $value) {
				if($value['user_id'] == $param['value']) {
					return false;
				}
			}
			return true;
		}

		function checkAlt($value) {
			if($this->request['mode'] == 'update') {
				$row = $this->table->selectByPk($value);
				if($this->session['init_value']['update_datetime'] < $row['update_datetime']) {
					$error_message = __('Another user has updated this record');
					$this->form->setValue($this->session['init_value']);
					$this->form->checkAlt($row, $error_message);
					$this->form->setValue($value);
					$this->control = new B_Element($this->input_control_config, $this->user_auth);

					return false;
				}
			}

			return true;
		}

		function back() {
			$this->form->setValue($this->session['post']);
			$this->control = new B_Element($this->input_control_config, $this->user_auth, $this->mode);
		}

		function register() {
			// start transaction
			$this->db->begin();

			$ret = $this->_register($message);

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
				return;
			}
			// end transaction

			$this->result = new B_Element($this->result_config, $this->user_auth);
			$this->result_control = new B_Element($this->result_control_config, $this->user_auth);

			$param['user_id'] = $this->post['user_id'];
			$param['action_message'] = $message;
			$this->result->setValue($param);

			$this->setView('result_view');
		}

		function _register(&$message) {
			if(!$this->checkAlt($this->session['post'])) {
				$message = __('Another user has updated this record');
				return false;
			}

			$param = $this->session['post'];

			switch($this->mode) {
			case 'insert':
				$ret = $this->insert();
				if($ret) {
					$message = __('was saved.');
				}
				else {
					$message = __('was faild to register.');
				}
				break;

			case 'update':
				$param['update_user'] = $this->user_id;
				$param['update_datetime'] = time();
				$ret = $this->table->update($param);
				if($ret) {
					$message = __('was updated.');
				}
				else {
					$message = __('was faild to update.');
				}
				break;

			case 'delete':
				$ret = $this->table->deleteByPk($param);
				if($ret) {
					$message = __('was deleted.');
				}
				else {
					$message = __('was faild to delete.');
				}
				break;
			}

			return $ret;
		}

		function insert() {
			$param = $this->session['post'];

			$param['id'] = '';
			$param['del_flag'] = '0';
			$param['create_user'] = $this->user_id;
			$param['create_datetime'] = time();
			$param['update_user'] = $this->user_id;
			$param['update_datetime'] = time();

			return $this->table->selectInsert($param);
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/user.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox_white.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function result_view() {
			// Start buffering
			ob_start();

			require_once('./view/view_result.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/user.css" type="text/css" rel="stylesheet" media="all" />');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
