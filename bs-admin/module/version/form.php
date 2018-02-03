<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class version_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			$this->mode = $this->request['mode'];

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);
			$this->result = new B_Element($result_config);
			$this->result_control = new B_Element($result_control_config);

			$this->table = new B_Table($this->db, 'version');

			$this->input_control_config = $input_control_config;
			$this->confirm_control_config = $confirm_control_config;
			$this->delete_control_config = $delete_control_config;

			// Set mode to HTML
			$obj = $this->form->getElementByName('mode');
			$obj->setValue($this->request);
		}

		function select() {
			switch($this->mode) {
			case 'insert':
				$this->control = new B_Element($this->input_control_config);
				break;

			case 'update':
				$param['version_id'] = $this->request['version_id'];
				$row = $this->table->selectByPk($param);
				$this->form->setValue($row);
				$this->session['init_value'] = $row;

				$this->control = new B_Element($this->input_control_config);
				break;

			case 'delete':
				$this->control = new B_Element($this->delete_control_config);
				$row = $this->table->selectByPk($this->request);
				$this->form->setValue($row);
				$this->display_mode = 'confirm';
				break;
			}
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

			$this->form->getValue($param);
			$this->session['post'] = $param;
			$this->control = new B_Element($this->confirm_control_config);

			// Set display mode
			$this->display_mode = 'confirm';
		}

		function checkAlt($value) {
			if($this->mode == 'update') {
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

		function register() {
			if(!$this->checkAlt($this->session['post'])) {
				$message = __('Another user has updated this record');
				return false;
			}

			$param = $this->session['post'];
			$param['del_flag'] = '0';
			$param['update_datetime'] = time();
			$param['update_user'] = $this->user_id;

			// Change text date and time to UNIX date
			$param['publication_datetime_u'] = strtotime($param['publication_datetime_t']);

			$this->db->begin();
			if($this->mode == 'insert' && $this->session['post']['version_id'] == '') {
				// Update private_revision_id of latest version
				$sql = "select *
						from " . B_DB_PREFIX . "version
						where version_id =
						(select max(version_id)
						from " . B_DB_PREFIX . "version)";

				$rs = $this->db->query($sql);
				$row = $this->db->fetch_assoc($rs);

				$update_param = $row;
				$update_param['private_revision_id'] = str_pad(((int)$update_param['private_revision_id'] + 1), 2, '0', STR_PAD_LEFT);
				$update_param['update_datetime'] = time();
				$update_param['update_user'] = $this->user_id;

				$ret = $this->table->update($update_param);

				$param['cache'] = $row['cache'];
				$param['private_revision_id'] = '00';
				$param['create_user'] = $this->user_id;
				$param['create_datetime'] = time();

				$ret = $this->table->selectInsert($param);
				$param['action_message'] = __('was saved.');
			}
			else {
				$ret = $this->table->update($param);
				if($ret) {
					$sql = "select * from " . B_DB_PREFIX . "v_current_version";
					$rs = $this->db->query($sql);
					$row = $this->db->fetch_assoc($rs);
					if($row['reserved_version_id'] == $param['version_id'] || $row['current_version_id'] == $param['version_id']) {
						$this->createLimitFile(B_LIMIT_FILE_INFO, $row['publication_datetime_u']);
						if(file_exists(B_FILE_INFO_C)) {
							$row = $this->getCacheFromDB('current_version_id');
							$this->replaceCacheFile(B_FILE_INFO_C, B_FILE_INFO_SEMAPHORE_C, $row['cache']);
						}
					}
				}
				$param['action_message'] = __('was updated.');
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
				$param['action_message'] = __('was faild to register.');
			}
			$param['title'] = $this->session['post']['title'];
			$this->result->setValue($param);

			$this->setView('result_view');

			unset($this->session['folder_id']);
		}

		function delete() {
			$param = $this->post;
			$param['del_flag'] = '1';

			$this->db->begin();
			$row = $this->table->selectByPk($this->post);

			// Check version condition before delete
			$max_version_id = $this->table->selectMaxValue('version_id');
			if($row['version_id'] != $max_version_id) {
				$this->message = __('This version cannot be deleted because it is not ths most recent version.');
				$this->setView('error_view');
				return;
			}
			if($this->version['working_version_id'] == $row['version_id']) {
				$this->message = __('The working version cannot be deleted.');
				$this->setView('error_view');
				return;
			}
			if($this->version['current_version_id'] == $row['version_id']) {
				$this->message = __('The published version cannot be deleted.');
				$this->setView('error_view');
				return;
			}

			$ret = $this->deleteRecords($row['version_id']);

			$param = $row;

			if($ret) {
				$this->db->commit();
				$this->deleteResourceFiles();
				$param['action_message'] = __('was deleted.');
			}
			else {
				$this->db->rollback();
				$param['action_message'] = __('was failed to delete.');
			}
			$this->result->setValue($param);

			$this->setView('result_view');
		}

		function deleteRecords($version_id) {
			// save resrouce_node file info
			$sql = "select * from " . B_DB_PREFIX . "resource_node where version_id='$version_id'";
			$rs = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($rs)) {
				$this->delete_resource_files[] = $row;
			}

			try {
				$this->deleteVersionRecords($version_id, 'contents_node');
				$this->deleteVersionRecords($version_id, 'contents');
				$this->deleteVersionRecords($version_id, 'template_node');
				$this->deleteVersionRecords($version_id, 'template');
				$this->deleteVersionRecords($version_id, 'widget_node');
				$this->deleteVersionRecords($version_id, 'widget');
				$this->deleteVersionRecords($version_id, 'resource_node');
			}
			catch(Exception $e) {
				return false;
			}

			$param['version_id'] = $version_id;
			return $this->table->deleteByPk($param);
		}

		function deleteVersionRecords($version_id, $table) {
			$sql = "delete from " . B_DB_PREFIX . "$table where version_id='$version_id'";
			$status = $this->db->query($sql);

			if(!$status) {
				throw new Exception(str_replace('%TABLE_NAME%', __('Failed to delete version records (%TABLE_NAME%)'), $table));
			}
		}

		function deleteResourceFiles() {
			if(!is_array($this->delete_resource_files)) return;

			foreach($this->delete_resource_files as $row) {
				$info = pathinfo($row['node_name']);
				$file_name = B_RESOURCE_DIR . $row['contents_id'] . '.' . strtolower($info['extension']);
				switch($info['extension']) {
				case 'avi':
				case 'flv':
				case 'mp4':
				case 'mpg':
				case 'mpeg':
				case 'wmv':
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.jpg';
					break;

				default:
					$thumb_file_name = B_RESOURCE_DIR . B_THUMB_PREFIX . $row['contents_id'] . '.' . strtolower($info['extension']);
					break;
				}

				if(file_exists($file_name)) {
					unlink($file_name);
				}
				if(file_exists($thumb_file_name)) {
					unlink($thumb_file_name);
				}
			}
		}

		function back() {
			$this->form->setValue($this->session['post']);
			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			if($this->mode == 'insert') {
				$obj = $this->form->getElementByName('version_id_row');
				$obj->display = 'none';
			}
			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/version.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js"></script>');

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

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/version.css">');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function error_view() {
			// Start buffering
			ob_start();

			require_once('./view/view_error.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/version.css">');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
