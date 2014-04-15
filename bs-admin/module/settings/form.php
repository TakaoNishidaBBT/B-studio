<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class settings_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);

			$this->main_table = new B_Table($this->db, 'settings');

			$this->result_config = $result_config;
			$this->result_control_config = $result_control_config;
			$this->input_control_config = $input_control_config;
			$this->confirm_control_config = $confirm_control_config;

			if(class_exists('ZipArchive')) {
				$obj = $this->form->getElementByName('full_backup');
				$obj->display = 'block';
			}
		}

		function func_default() {
			$this->select();
		}

		function select() {
			$this->session = '';

			$this->control = new B_Element($this->input_control_config);
			$param['id'] = '00001';
			$row = $this->main_table->selectByPk($param);

			$this->form->setValue($row);
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

		function regist() {
			$param = $this->session['request'];
			$param['id'] = '00001';
			$param['del_flag'] = '0';

			$this->db->begin();

			if($this->main_table->selectByPk($param)) {
				$ret = $this->main_table->update($param);
			}
			else {
				$ret = $this->main_table->insert($param);
			}

			if($ret) {
				$this->db->commit();
				$param['action_message'] = '<p><strong>基本設定：登録しました</strong></p>';
			}
			else {
				$this->db->rollback();
				$param['action_message'] = '<p><strong>基本設定：登録に失敗しました</strong></p>';
			}
			$this->result = new B_Element($this->result_config);
			$this->result_control = new B_Element($this->result_control_config);

			$this->result->setValue($param);

			$this->setView('view_result');
		}

		function back() {
			$this->form->setValue($this->session['request']);
			$this->control = new B_Element($this->input_control_config);
		}

		function backupAll() {
			if(!class_exists('ZipArchive')) exit;

			// set time limit to 3 minutes
			set_time_limit(180);

			$zip = new ZipArchive();
			$file_name = B_SITE_NAME . '_' . date("YmdHis") . '.zip';
			$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

			if(!$zip->open($file_path, ZipArchive::CREATE)) {
				exit;
			}

			// ブラウザを閉じても処理を継続
			ignore_user_abort(true);

			$node = new B_FileNode(B_ADMIN_FILES_DIR, 'root', null, null, 'all');
			$node->serializeForDownload($admin_file_data);
			if(is_array($admin_file_data)) {
				foreach($admin_file_data as $key => $value) {
					if($value) {
						$zip->addFile($value, B_ADMIN_FILES . $key);
					}
					else {
						$zip->addEmptyDir(B_ADMIN_FILES . $key);
					}
				}
			}

			$node = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
			$node->serializeForDownload($file_data);
			if(is_array($file_data)) {
				foreach($file_data as $key => $value) {
					if($value) {
						$zip->addFile($value, B_UPLOAD_FILES . $key);
					}
					else {
						$zip->addEmptyDir(B_UPLOAD_FILES . $key);
					}
				}
			}

			$dump_file_name = B_SITE_NAME . '_' . date("YmdHis") . '.sql';
			$dump_file_path = B_DOWNLOAD_DIR . $dump_file_name;
			if(!$this->db->backupTables($dump_file_path)) {
				$this->result = new B_Element($this->result_config);
				$this->result_control = new B_Element($this->result_control_config);
				$param['action_message'] = '<p class="error-message">' . $this->db->getErrorMsg() . '</p>';
				$this->result->setValue($param);
				$this->setView('view_result');

				return;
			}
			$zip->addFile($dump_file_path, B_DUMP_FILE . $dump_file_name);

			$zip->close();

			header('content-disposition: attachment; filename='.$file_name);
			header('Content-type: application/sql');
			header('Cache-control: public');
			header('Pragma: public');

			readfile($file_path);
			unlink($file_path);
			unlink($dump_file_path);
			exit;
		}

		function backupDB() {
			$dump_file_name = B_SITE_NAME . '_' . date("YmdHis") . '.sql';
			$file_path = B_DOWNLOAD_DIR . $dump_file_name;
			if(!$this->db->backupTables($file_path)) {
				$this->result = new B_Element($this->result_config);
				$this->result_control = new B_Element($this->result_control_config);
				$param['action_message'] = '<p class="error-message">' . $this->db->getErrorMsg() . '</p>';
				$this->result->setValue($param);
				$this->setView('view_result');

				return;
			}

			header('content-disposition: attachment; filename='.$dump_file_name);
			header('Content-type: application/sql');
			header('Cache-control: public');
			header('Pragma: public');

			readfile($file_path);
			unlink($file_path);
			exit;
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/settings.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_form.php');
		}

		function view_result() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/settings.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_result.php');
		}
	}
