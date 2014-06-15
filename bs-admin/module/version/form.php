<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class version_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);
			$this->result = new B_Element($result_config);
			$this->result_control = new B_Element($result_control_config);

			$this->main_table = new B_Table($this->db, 'version');

			$this->input_control_config = $input_control_config;
			$this->confirm_control_config = $confirm_control_config;
			$this->delete_control_config = $delete_control_config;
		}

		function select() {
			$this->session['mode'] = $this->request['mode'];

			switch($this->request['mode']) {
			case 'delete':
				$this->control = new B_Element($this->delete_control_config);
				$row = $this->main_table->selectByPk($this->request);
				$this->form->setValue($row);
				$this->display_mode = 'confirm';
				break;

			default:
				$this->control = new B_Element($this->input_control_config);
				if($this->request['version_id']) {
					$row = $this->main_table->selectByPk($this->request);
					$this->form->setValue($row);
				}
				break;
			}
		}

		function confirm() {
			$this->form->setValue($this->request);

			$this->status = $this->form->validate();

			if($this->status) {
				// 表示モードを確認モードに設定
				$this->display_mode = "confirm";
				$this->form->getValue($param);
				$this->session['request'] = $param;
				$this->control = new B_Element($this->confirm_control_config);
			}
			else {
				$this->control = new B_Element($this->input_control_config);
			}
		}

		function regist() {
			$param = $this->session['request'];
			$param['del_flag'] = "0";
			$param["update_datetime"] = time();
			$param["update_user"] = $this->user_id;
			// UNIXタイムに変換
			$param['publication_datetime_u'] = strtotime($param['publication_datetime_t']);

			$this->db->begin();
			if($this->session['mode'] == 'insert' && $this->session['request']['version_id'] == '') {
				// 現在の最新バージョンのprivate_revision_idをインクリメント
				$sql = "select *
						from " . B_DB_PREFIX . "version
						where version_id =
						(select max(version_id)
						from " . B_DB_PREFIX . "version)";

				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				$update_param = $row;
				$update_param['private_revision_id'] = str_pad(((int)$update_param['private_revision_id'] + 1), 2, '0', STR_PAD_LEFT);
				$update_param["update_datetime"] = time();
				$update_param["update_user"] = $this->user_id;

				$ret = $this->main_table->update($update_param);

				$param['private_revision_id'] = '00';
				$param['create_user'] = $this->user_id;
				$param["create_datetime"] = time();
				$ret = $this->main_table->selectInsert($param);
				$param['action_message'] = "を登録しました。";
			}
			else {
				$ret = $this->main_table->update($param);
				if($ret) {
					$sql = "select * from " . B_DB_PREFIX . "v_current_version";
					$rs = $this->db->query($sql);
					$row = $this->db->fetch_assoc($rs);
					if($row['reserved_version_id'] == $param['version_id'] || $row['current_version_id'] == $param['version_id']) {
						$this->createLimitFile(B_LIMIT_FILE_INFO, $row['publication_datetime_u']);
						if(file_exists(B_FILE_INFO_C)) {
							unlink(B_FILE_INFO_C);
						}
					}
				}
				$param['action_message'] = "を更新しました。";
			}

			if($ret) {
				$sql = "select * from " . B_DB_PREFIX . "v_current_version";
				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);
			}

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
				$param['action_message'] = "の登録に失敗しました。";
			}
			$param['title'] = $this->session['request']['title'];
			$this->result->setValue($param);

			$this->setView('regist_view');

			unset($this->session['folder_id']);
		}

		function delete() {
			$param = $this->post;
			$param['del_flag'] = "1";

			$this->db->begin();
			$row = $this->main_table->selectByPk($this->post);

			// 最新バージョンかどうかの最終チェック
			$max_version_id = $this->main_table->selectMaxValue('version_id');
			if($row['version_id'] != $max_version_id) {
				$this->message = '最新バージョンではありませんので削除できません。';
				$this->setView('error_view');
				return;
			}
			if($this->version['working_version_id'] == $row['version_id']) {
				$this->message = '作業中バージョンなので削除できません。';
				$this->setView('error_view');
				return;
			}
			if($this->version['current_version_id'] == $row['version_id']) {
				$this->message = '公開バージョンなので削除できません。';
				$this->setView('error_view');
				return;
			}

			$this->contents_table = new B_Table($this->db, 'contents');
			$this->contents_node_table = new B_Table($this->db, 'contents_node');
			$this->template_table = new B_Table($this->db, 'template');
			$this->template_node_table = new B_Table($this->db, 'template_node');
			$this->widget_table = new B_Table($this->db, 'widget');
			$this->widget_node_table = new B_Table($this->db, 'widget_node');
			$this->resource_node_table = new B_Table($this->db, 'resource_node');

			$ret = $this->main_table->deleteByPk($param);
			$ret&= $this->contents_table->deleteByPk($param);
			$ret&= $this->contents_node_table->deleteByPk($param);
			$ret&= $this->template_table->deleteByPk($param);
			$ret&= $this->template_node_table->deleteByPk($param);
			$ret&= $this->widget_table->deleteByPk($param);
			$ret&= $this->widget_node_table->deleteByPk($param);
			$ret&= $this->resource_node_table->deleteByPk($param);

			$param = $row;

			if($ret) {
				$this->db->commit();
				$param['action_message'] = 'を削除しました。';
			}
			else {
				$this->db->rollback();
				$param['action_message'] = 'の削除に失敗しました。';
			}
			$this->result->setValue($param);

			$this->setView('regist_view');
		}

		function back() {
			$this->form->setValue($this->session['request']);
			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			if($this->session['mode'] == 'insert') {
				$obj = $this->form->getElementByName('version_id_row');
				$obj->display = 'none';
			}

			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_form.php');
		}

		function regist_view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');
			$this->showHtmlHeader();

			require_once('./view/view_result.php');
		}

		function error_view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');
			$this->showHtmlHeader();

			require_once('./view/view_error.php');
		}
	}
