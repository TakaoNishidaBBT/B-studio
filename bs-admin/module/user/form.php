<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
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

			// mode(hidden)を設定
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
				$this->display_mode = "confirm";

				$this->control = new B_Element($this->delete_control_config, $this->user_auth);
				break;
			}
		}

		function confirm() {
			$this->form->setValue($this->post);

			if(!$this->form->validate()) {
				$this->action_message = '入力内容にエラーがあります';
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				return;
			}

			if(!$this->checkAlt($this->post)) {
				// 排他エラー
				$this->action_message = '他のユーザに更新されています';
				$this->control = new B_Element($this->input_control_config, $this->user_auth);
				return;
			}

			$this->form->getValue($post_value);
			$this->session['post'] = $post_value;
			$this->control = new B_Element($this->confirm_control_config, $this->user_auth);

			// 表示モードを確認モードに設定
			$this->display_mode = 'confirm';
		}

		function _validate_callback($param) {
			// 発送先IDの二重登録確認
			$sql = "select count(*) cnt from " . B_DB_PREFIX . $this->table_name . " where user_id='" . $param['value'] . "'";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			if($row['cnt'] == 0) {
				return true;
			}
			return false;
		}

		function _validate_callback2($param) {
			// スーパユーザのID確認
			global $g_auth_users;
			foreach($g_auth_users as $value) {
				if($value['user_id'] == $param['value']) {
					return false;
				}
			}
			return true;
		}

		function checkAlt($value) {
			$status = true;

			if($this->request['mode'] == 'update') {
				$row = $this->table->selectByPk($value);
				if($this->session['init_value']['update_datetime'] < $row['update_datetime']) {
					$status = false;

					// 排他エラー
					$error_message = '他のユーザによって更新されています';
					$this->action_message = $error_message;

					$this->form->setValue($this->session['init_value']);

					$this->form->checkAlt($row, $error_message);

					$this->control = new B_Element($this->input_control_config, $this->user_auth);
				}
			}
			$this->form->setValue($value);

			return $status;
		}

		function back() {
			$this->form->setValue($this->session['post']);
			$this->control = new B_Element($this->input_control_config, $this->user_auth, $this->mode);
		}

		function regist() {
			// start transaction
			$this->db->begin();

			$ret = $this->_regist($message);

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
			}
			// end transaction

			$this->result = new B_Element($this->result_config, $this->user_auth);
			$this->result_control = new B_Element($this->result_control_config, $this->user_auth);

			$param['user_id'] = $this->post['user_id'];
			$param['action_message'] = $message;
			$this->result->setValue($param);

			$this->setView('regist_view');
		}

		function _regist(&$message) {
			if(!$this->checkAlt($this->session['post'])) {
				// 排他エラー
				$message = '他のユーザに更新されています';
				return false;
			}

			if(!$this->form->validate()) {
				$message = '入力内容にエラーがあります';
				return false;
			}

			$param = $this->session['post'];

			switch($this->mode) {
			case 'insert':
				$ret = $this->insert($this->new_id);
				if($ret) {
					$message = 'のレコードを登録しました。';
				}
				else {
					$message = 'のレコードの登録に失敗しました。';
				}
				break;

			case 'update':
				$param['update_user'] = $this->user_id;
				$param['update_datetime'] = time();
				$ret = $this->table->update($param);
				if($ret) {
					$message = 'のレコードを更新しました。';
				}
				else {
					$message = 'のレコードの更新に失敗しました。';
				}
				break;

			case 'delete':
				$ret = $this->table->deleteByPk($param);
				if($ret) {
					$message= 'のレコードを削除しました。';
				}
				else {
					$message = 'のレコードの削除に失敗しました。';
				}
				break;
			}

			return $ret;
		}

		function insert(&$new_id) {
			// 初期値
			$param = $this->session['post'];

			$param['id'] = '';
			$param['create_user'] = $this->user_id;
			$param['create_datetime'] = time();
			$param['update_user'] = $this->user_id;
			$param['update_datetime'] = time();

			$ret = $this->table->selectInsert($param);
			$new_id = $this->table->selectMaxValue('id');
			
			return $ret;
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/user.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_form.php');
		}

		function regist_view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/user.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_result.php');
		}
	}
