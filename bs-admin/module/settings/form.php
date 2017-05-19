<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
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
			define('BACKUP_PROGRESS_SIZE', 500 * 1024 * 1024);
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
				$param['action_message'] = '<p><strong>' . __('Basic settings: Saved') . '</strong></p>';
			}
			else {
				$this->db->rollback();
				$param['action_message'] = '<p><strong>' . __('Basic settings: Failed') . '</strong></p>';
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
			if($this->request['mode'] == 'download') {
				$this->downloadArchive($this->request['file_name'], $this->request['file_path']);
			}
			else {
				$this->createArchive();
			}
		}

		function createArchive() {
			if(!class_exists('ZipArchive')) exit;

			// Set time limit to infinity
			set_time_limit(0);

			$file_name = 'bstudio_' . date('YmdHis') . '.zip';
			$file_path = B_DOWNLOAD_DIR . $this->user_id . time() . $file_name;

			// Continue no matter whether a client disconnect or not
			ignore_user_abort(true);

			// send progress
			header('Content-Type: application/octet-stream');
			header('Transfer-encoding: chunked');
			flush();
			ob_flush();

			// Send start message
			$response['status'] = 'show';
			$response['progress'] = 0;
			$response['message'] = 'Creating archive file';
			$progress = 0;
			$this->sendChunk(json_encode($response));

			// create archive file
			$cmdline = 'php ' . B_DOC_ROOT . B_ADMIN_ROOT . 'module/settings/archive.php';
			$cmdline .= ' ' . $_SERVER['SERVER_NAME'];
			$cmdline .= ' ' . $_SERVER['DOCUMENT_ROOT'];
			$cmdline .= ' ' . $file_path;
			$cmdline .= ' ' . $this->request['mode'];

			// kick as a background process
			B_Util::fork($cmdline);

			$resource_node_table = B_DB_PREFIX . B_RESOURCE_NODE_TABLE;
			$sql = "select sum(file_size) total_size
					from $resource_node_table
					where del_flag <> '1'";

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			$resource_total_size = $row['total_size'];
			$node = new B_FileNode(B_UPLOAD_DIR, 'root', null, null, 'all');
			$files_total_size = $node->filesize();
			$total_file_size = $resource_total_size + $files_total_size;

			// send progress 
			for($cnt=0 ;; $cnt++) {
				usleep(40000);
				if(file_exists($file_path)) {
					$response['status'] = 'progress';
					$response['progress'] = 100;
					$this->sendChunk(',' . json_encode($response));
					usleep(300000);
					$response['status'] = 'complete';
					$response['progress'] = 100;
					$response['message'] = 'Complete !';
					$this->sendChunk(',' . json_encode($response));
					sleep(1);

					break;
				}

				if($cnt%4 == 0) {
					unset($dots);
					for($i=0; $i<($cnt/4%8); $i++) {
						$dots.= '.';
					}
					$response['status'] = 'message';
					$response['message'] = "Creating archive file {$dots}";

					$this->sendChunk(',' . json_encode($response));
				}

				usleep(40000);

				$response['status'] = 'progress';
				$response['progress'] = round($cnt / $total_file_size * 100 * 500000);
				if($response['progress'] > 99) $response['progress'] = 99;

				if($progress != $response['progress']) {
					$this->sendChunk(',' . json_encode($response));
					$progress = $response['progress'];
				}
			}

			// finish
			$response['status'] = 'download';
			$response['remove'] = true;
			$response['file_name'] = $file_name;
			$response['file_path'] = $file_path;
			$this->sendChunk(',' . json_encode($response));
			$this->sendChunk();	// terminate
			if(connection_status()) {
				unlink($file_path);
			}
			exit;
		}

		function downloadArchive($file_name, $file_path) {
			// Continue no matter whether a client download or cancel or disconnect
			ignore_user_abort(true);

			header('content-disposition: attachment; filename='.$file_name);
			header('Content-type: application/sql');
			header('Cache-control: public');
			header('Pragma: public');

			ob_end_clean();
			readfile($file_path);
			unlink($file_path);

			exit;
		}

		function backupDB() {
			// Continue no matter whether a client download or cancel or disconnect
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
			$this->html_header->appendProperty('script', '<script src="js/bframe_progress_bar.js" type="text/javascript"></script>');

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
