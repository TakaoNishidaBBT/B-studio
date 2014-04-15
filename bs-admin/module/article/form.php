<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class article_form extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/form_config.php');
			$this->form = new B_Element($form_config);
			$this->result = new B_Element($result_config);
			$this->result_control = new B_Element($result_control_config);

			$this->main_table = new B_Table($this->db, 'article');

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
				$this->setPhoto($row['title_img_file']);
				$this->form->setValue($row);
				$this->display_mode = 'confirm';
				break;

			default:
				$this->control = new B_Element($this->input_control_config);
				if($this->request['article_id']) {
					$sql = "select a.*
								  ,b.node_name category_name
							from " . B_DB_PREFIX . "article a
							left join " . B_DB_PREFIX . "category b
							on a.category_id = b.node_id
							and b.del_flag='0'
							where a.article_id = '%ARTICLE_ID%'";

					$sql = str_replace('%ARTICLE_ID%', $this->request['article_id'], $sql);

					$rs=$this->db->query($sql);
					$row=$this->db->fetch_assoc($rs);

					$this->setPhoto($row['title_img_file']);
					$this->form->setValue($row);
					if($row['description_flag'] != '1') {
						$obj =&$this->form->getElementByName('preview');
						$obj->disabled = 'disabled';
					}
				}
				break;
			}
		}

		function confirm() {
			$this->setPhoto($this->post['title_img_file']);

			$this->form->setValue($this->request);

			if($this->post['external_link'] && !$this->post['url']) {
				$obj = $this->form->getElementByName('url');
				$obj->status = false;
			}

			$this->status = $this->form->validate();

			if($this->status) {
				// 表示モードを確認モードに設定
				$this->filter = 'confirm';
				$this->display_mode = 'confirm';
				$this->form->getValue($param);
				$this->session['request'] = $param;

				if($this->post['description_flag'] == '1') {
					$obj = $this->form->getElementByName('external_link_row');
					$obj->display = 'none';
				}
				else {
					$obj = $this->form->getElementByName('description_row');
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

				$this->control = new B_Element($this->confirm_control_config);
			}
			else {
				$this->control = new B_Element($this->input_control_config);
			}
		}

		function setPhoto($img_path) {
			if($img_path) {
				if(!file_exists(B_UPLOAD_DIR . $img_path)) {
					return;
				}

				$image_size = getimagesize(B_UPLOAD_DIR . $img_path);

				if($image_size[0] > 110) {
					if($image_size[0] > $image_size[1]) {
						$width = 110;
						$height = $image_size[1] * $width / $image_size[0];
					}
					else {
						$height = 80;
						$width = $image_size[0] * $height / $image_size[1];
					}
				}
				else if($image_size[1] > 80) {
					$height = 80;
					$width = $image_size[0] * $height / $image_size[1];
				}
				else {
					$width = $image_size[0];
					$height = $image_size[1];
				}

				$html = '<img src="%IMG_URL%" width="%WIDTH%" height="%HEIGHT%" />';
				$html = str_replace('%IMG_URL%', B_UPLOAD_URL . $img_path, $html);
				$html = str_replace('%WIDTH%', $width, $html);
				$html = str_replace('%HEIGHT%', $height, $html);
				$obj =& $this->form->getElementByName('title_img');
				$obj->value = $html;
			}
		}

		function regist() {
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
				$param['action_message'] = 'を登録しました。';
			}
			else {
				$param['update_user'] = $this->user_id;
				$param['update_datetime'] = time();
				$ret = $this->main_table->update($param);
				$param['action_message'] = 'を更新しました。';
			}

			if($ret) {
				$this->db->commit();
			}
			else {
				$this->db->rollback();
				$param['action_message'] = 'の登録に失敗しました';
			}
			$this->result->setValue($param);

			$this->setView('regist_view');
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
			$this->setPhoto($this->session['request']['title_img_file']);

			$this->control = new B_Element($this->input_control_config);
		}

		function view() {
			if($this->session['mode'] == 'insert') {
				$obj = $this->form->getElementByName('article_id_row');
				$obj->display = 'none';
			}

			$this->form->setFilterValue($this->filter);

			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/article.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/calendar.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_edit_check.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/ckeditor/ckeditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_visualeditor.js" type="text/javascript"></script>');
			$this->html_header->appendProperty('script', '<script src="js/bframe_calendar.js" type="text/javascript"></script>');

			$this->showHtmlHeader();

			require_once('./view/view_form.php');
		}

		function regist_view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css','<link href="css/article.css" type="text/css" rel="stylesheet" media="all">');
			$this->showHtmlHeader();

			require_once('./view/view_result.php');
		}
	}
