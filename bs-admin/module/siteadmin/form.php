<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class siteadmin_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			if($this->user_auth != 'super_admin') exit;

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);

			$this->result_config = $result_config;
			$this->result_control_config = $result_control_config;
			$this->input_control_config = $input_control_config;
			$this->confirm_control_config = $confirm_control_config;
		}

		function func_default() {
			$this->select();
		}

		function select() {
			global $g_auth_users;

			$param['admin_user_name'] = $g_auth_users[0]['user_name'];
			$param['admin_user_id'] = $g_auth_users[0]['user_id'];
			$param['language'] = $g_auth_users[0]['language'];

			$this->form->setValue($param);
			$this->control = new B_Element($this->input_control_config);
		}

		function confirm() {
			$this->form->setValue($this->request);

			$this->status = $this->form->validate();

			if(!$this->form->validate()) {
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				return;
			}

			$this->form->getValue($param);
			$this->session['request'] = $param;

			$this->control = new B_Element($this->confirm_control_config);

			// Set display mode
			$this->display_mode = 'confirm';
		}

		function _validate_callback($param) {
			// Check user id already exists
			$sql = "select count(*) cnt from " . B_DB_PREFIX . "user where user_id = binary '" . $param['value'] . "'";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			if($row['cnt'] == 0) {
				return true;
			}
			return false;
		}

		function register() {
			global $g_auth_users;

			$param = $this->session['request'];

			// Set up admin user file
			$contents = file_get_contents(B_DOC_ROOT . B_ADMIN_ROOT . 'user/config/_users.php');
			$contents = str_replace('%USER_NAME%',  $param['admin_user_name'], $contents);
			$contents = str_replace('%USER_ID%',  $param['admin_user_id'], $contents);
			$contents = str_replace('%LANGUAGE%',  $param['language'], $contents);
			if($param['admin_user_pwd']) {
				$contents = str_replace('%PASSWORD%', md5($param['admin_user_pwd']), $contents);
			}
			else {
				$contents = str_replace('%PASSWORD%', $g_auth_users[0]['pwd'], $contents);
			}

			file_put_contents(B_DOC_ROOT . B_ADMIN_ROOT . 'user/users.php', $contents);

			$param['action_message'] = '<p><span class="bold">' . __('The site admin settings has been updated') . '</span></p>';

			$this->result = new B_Element($this->result_config);
			$this->result_control = new B_Element($this->result_control_config);

			$this->result->setValue($param);

			$this->setView('result_view');
		}

		function back() {
			$this->form->setValue($this->session['request']);
			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/siteadmin.css" type="text/css" rel="stylesheet" media="all" />');
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

			$this->html_header->appendProperty('css', '<link href="css/siteadmin.css" type="text/css" rel="stylesheet" media="all" />');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
