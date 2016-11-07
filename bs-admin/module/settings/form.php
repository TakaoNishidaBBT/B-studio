<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
				$obj = $this->form->getElementByName('full_backup2');
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
				$this->display_mode = 'confirm';
				$this->form->getValue($param);
				$this->session['request'] = $param;

				$this->control = new B_Element($this->confirm_control_config);
			}
			else {
				$this->control = new B_Element($this->input_control_config);
			}
		}

		function register() {
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

			// Set up lang_config
			$contents = file_get_contents(B_LNGUAGE_DIR . 'config/_lang_config.php');
			$contents = str_replace('%LANGUAGE%',  $param['language'], $contents);
			file_put_contents(B_DOC_ROOT . B_ADMIN_ROOT . 'config/lang_config.php', $contents);

			if($ret) {
				$this->db->commit();
				$param['action_message'] = '<p><strong>' . __('Configuration: Saved') . '</strong></p>';
			}
			else {
				$this->db->rollback();
				$param['action_message'] = '<p><strong>' . __('Configuration: Failed') . '</strong></p>';
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

			// Set time limit to 3 minutes
			set_time_limit(180);

			$zip = new ZipArchive();
			$file_name = 'bstudio_' . date('YmdHis') . '.zip';
			$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

			if(!$zip->open($file_path, ZipArchive::CREATE)) {
				exit;
			}

			// Continue whether a client disconnect or not
			ignore_user_abort(true);

			$node = new B_FileNode(B_ADMIN_FILES_DIR, 'root', null, null, 'all');
			$node->serializeForDownload($admin_file_data);
			if(is_array($admin_file_data)) {
				foreach($admin_file_data as $key => $value) {
					if($value) {
						$info = pathinfo($key);
						if(substr($info['basename'], 0, 1) == '.') continue;
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
						$info = pathinfo($key);
						if(substr($info['basename'], 0, 1) == '.') continue;
						$zip->addFile($value, B_UPLOAD_FILES . $key);
					}
					else {
						$zip->addEmptyDir(B_UPLOAD_FILES . $key);
					}
				}
			}

			$dump_file_name = 'bstudio_' . date('YmdHis') . '.sql';
			$dump_file_path = B_DOWNLOAD_DIR . $dump_file_name;
			if(!$this->db->backupTables($dump_file_path, $this->request['mode'])) {
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

			ob_end_clean();
			readfile($file_path);
			unlink($file_path);
			unlink($dump_file_path);
			exit;
		}

		function backupDB() {
			// Continue whether a client disconnect or not
			ignore_user_abort(true);

			$dump_file_name = 'bstudio_' . date('YmdHis') . '.sql';
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

			ob_end_clean();
			readfile($file_path);
			unlink($file_path);
			exit;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/settings.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox_white.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function view_result() {
			// Start buffering
			ob_start();

			require_once('./view/view_result.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/settings.css" type="text/css" rel="stylesheet" media="all" />');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
