<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class article2_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);
			$this->result = new B_Element($result_config);
			$this->result_control = new B_Element($result_control_config);

			$this->main_table = new B_Table($this->db, 'article2');

			$this->input_control_config = $input_control_config;
			$this->confirm_control_config = $confirm_control_config;
			$this->delete_control_config = $delete_control_config;

			$this->filter = 'select';
		}

		function select() {
			$this->session['mode'] = $this->request['mode'];

			switch($this->request['mode']) {
			case 'delete':
				$this->filter = 'delete';
				$this->control = new B_Element($this->delete_control_config);
				$row = $this->main_table->selectByPk($this->request);
				$this->setThumnail($row['title_img_file']);
				$this->form->setValue($row);
				$this->display_mode = 'confirm';
				break;

			default:
				$this->control = new B_Element($this->input_control_config);
				if($this->request['article_id']) {
					$sql = "select a.*
								  ,b.node_name category_name
							from " . B_DB_PREFIX . "article2 a
							left join " . B_DB_PREFIX . "category2 b
							on a.category_id = b.node_id
							and b.del_flag='0'
							where a.article_id = '%ARTICLE_ID%'";

					$sql = str_replace('%ARTICLE_ID%', $this->request['article_id'], $sql);

					$rs=$this->db->query($sql);
					$row=$this->db->fetch_assoc($rs);

					$this->setThumnail($row['title_img_file']);
					$this->form->setValue($row);
				}
				break;
			}
		}

		function confirm() {
			$this->setThumnail($this->post['title_img_file']);

			$this->form->setValue($this->request);

			if($this->post['external_link'] && !$this->post['url']) {
				$obj = $this->form->getElementByName('url');
				$obj->status = false;
			}

			if(!$this->form->validate()) {
				$this->control = new B_Element($this->input_control_config);
				return;
			}


			if($this->post['description_flag'] == '1') {
				$obj = $this->form->getElementByName('external_link_row');
				$obj->display = 'none';
			}
			else {
				$obj = $this->form->getElementByName('contents_row');
				$obj->display = 'none';

				if(!$this->post['external_link']) {
					$obj = $this->form->getElementByName('external_link_none');
					$obj->display = '';
					$obj = $this->form->getElementByName('url');
					$obj->display = 'none';
					$obj = $this->form->getElementByName('external_window');
					$obj->display = 'none';
				}
			}

			$this->form->getValue($param);
			$this->session['request'] = $param;

			$this->control = new B_Element($this->confirm_control_config);

			// Set display mode
			$this->display_mode = 'confirm';
			$this->filter = 'confirm';
		}

		function setThumnail($img_path) {
			if(!$img_path) return;
			if(!file_exists(B_UPLOAD_DIR . $img_path)) return;

			$file_info = pathinfo($img_path);
			$thumnail_path = $this->util->getPath(B_UPLOAD_URL, $this->util->getPath($file_info['dirname'], B_THUMB_PREFIX . $file_info['basename']));
			$html = '<img src="' . $thumnail_path . '" alt="" />';
			$obj = $this->form->getElementByName('title_img');
			$obj->value = $html;
		}

		function register() {
			$param = $this->session['request'];
			$param['del_flag'] = '0';
			$param['article_date_u'] = strtotime($param['article_date_t']);

			$this->db->begin();
			if($this->session['mode'] == 'insert' && $param['article_id'] == '') {
				$param['create_user'] = $this->user_id;
				$param['create_datetime'] = time();
				$param['update_user'] = $this->user_id;
				$param['update_datetime'] = time();
				$ret = $this->main_table->selectInsert($param);
				$param['article_id'] = $this->main_table->selectMaxValue('article_id');
				$param['action_message'] = __('was saved.');
			}
			else {
				$param['update_user'] = $this->user_id;
				$param['update_datetime'] = time();
				$ret = $this->main_table->update($param);
				$param['action_message'] = __('was saved.');
			}

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
				$param['action_message'] = __('was faild to saved.');
			}
			$this->result->setValue($param);

			$this->setView('resultView');
		}

		function delete() {
			$param = $this->post;
			$param['del_flag'] = '1';
			$param['update_user'] = $this->user_id;
			$param['update_datetime'] = time();

			$this->db->begin();
			$ret = $this->main_table->update($param);
			$row = $this->main_table->selectByPk($this->post);
			$param = $row;

			if($ret) {
				$this->db->commit();
				$param['action_message'] = __('was deleted.');
			}
			else {
				$this->db->rollback();
				$param['action_message'] = __('was faild to delete.');
			}
			$this->result->setValue($param);

			$this->setView('resultView');
		}

		function back() {
			$this->form->setValue($this->session['request']);
			$this->setThumnail($this->session['request']['title_img_file']);

			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			if($this->session['mode'] == 'insert') {
				$obj = $this->form->getElementByName('article_id_row');
				$obj->display = 'none';
			}
			$this->form->setFilterValue($this->filter);

			// Start buffering
			ob_start();

			require_once('./view/view_form.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/article.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/calendar.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ckeditor/ckeditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_visualeditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_calendar.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function resultView() {
			// Start buffering
			ob_start();

			require_once('./view/view_result.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css','<link href="css/article.css" type="text/css" rel="stylesheet" media="all">');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
