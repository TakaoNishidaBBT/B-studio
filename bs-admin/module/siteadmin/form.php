<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class siteadmin_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$auth = new B_AdminAuth;
			$ret = $auth->getUserInfo($user_id, $this->user_name, $this->user_auth);
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

			$this->form->setValue($param);
			$this->control = new B_Element($this->input_control_config);
		}

		function confirm() {
			$this->form->setValue($this->request);

			$this->status = $this->form->validate();

			if($this->status) {
				// 表示モードを確認モードに設定
				$this->display_mode = 'confirm';
				$this->form->getValue($param);
				$this->session['request'] = $param;

				$this->control = new B_Element($this->confirm_control_config);
			}
			else {
				$this->control = new B_Element($this->input_control_config);
			}
		}

		function _validate_callback($param) {
			// 発送先IDの二重登録確認
			$sql = "select count(*) cnt from " . B_DB_PREFIX . "user where user_id='" . $param['value'] . "'";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			if($row['cnt'] == 0) {
				return true;
			}
			return false;
		}

		function regist() {
			global $g_auth_users;

			$param = $this->session['request'];

			// setup admin user file
			$contents = file_get_contents(B_DOC_ROOT . B_ADMIN_ROOT . 'user/config/users.php_');
			$contents = str_replace('%USER_NAME%',  $param['admin_user_name'], $contents);
			$contents = str_replace('%USER_ID%',  $param['admin_user_id'], $contents);
			if($param['admin_user_pwd']) {
				$contents = str_replace('%PASSWORD%', md5($param['admin_user_pwd']), $contents);
			}
			else {
				$contents = str_replace('%PASSWORD%', $g_auth_users[0]['pwd'], $contents);
			}

			file_put_contents(B_DOC_ROOT . B_ADMIN_ROOT . 'user/users.php', $contents);

			$param['action_message'] = '<p><strong>サイト管理者の情報を更新しました</strong></p>';

			$this->result = new B_Element($this->result_config);
			$this->result_control = new B_Element($this->result_control_config);

			$this->result->setValue($param);

			$this->setView('view_result');
		}

		function back() {
			$this->form->setValue($this->session['request']);
			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/siteadmin.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_form.php');
		}

		function view_result() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/siteadmin.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_result.php');
		}
	}
