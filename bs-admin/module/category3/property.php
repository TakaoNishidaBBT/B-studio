<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class category3_property extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/property_config.php');
			$this->form = new B_Element($form_config);
			$this->config_form = new B_Element($config_form_config);

			$obj = $this->form->getElementByName('config_form');
			$obj->addElement($this->config_form);

			$this->tab_control = new B_Element($tab_control_config);

			$this->status = true;
		}

		function init() {
			$this->setView('view_folder');
		}

		function select() {
			if($this->request['node_id']) {
				$row = $this->getNodeInfo($this->request['node_id']);
				$this->form->setValue($row);
				$this->setThumnail($row['icon_file']);
			}
		}

		function getNodeInfo($node_id) {
			$sql = "select * from %VIEW% where node_id='$node_id'";
			$sql = str_replace('%VIEW%', B_DB_PREFIX . B_CATEGORY3_VIEW, $sql);
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			return $row;
		}

		function setThumnail($img_path) {
			if(!$img_path) return;
			if(!file_exists(B_UPLOAD_DIR . $img_path)) return;

			$file_info = pathinfo($img_path);
			$thumnail_path = $this->util->getPath(B_UPLOAD_URL, $this->util->getPath($file_info['dirname'], B_THUMB_PREFIX . $file_info['basename']));
			$html = '<img src="' . $thumnail_path . '" alt="" />';
			$obj = $this->form->getElementByName('icon');
			$obj->value = $html;
		}

		function register() {
			$this->form->setValue($this->post);
			$this->form->getValue($param);
			$this->update($param);
			$response['status'] = $this->status;
			$response['mode'] = $this->mode;
			$response['message_obj'] = 'message';
			$response['message'] = $this->message;
			if($this->status && $this->mode != 'confirm') {
				$response['values'] = array('contents_id' => $contents_id, 'update_datetime' => time());
			}

			header('Content-Type: application/x-javascript charset=utf-8');
			echo json_encode($response);
			exit;
		}

		function update($param) {
			// start transaction
			$this->db->begin();

			$category_data = $param;
			$category_data['node_id'] = $param['node_id'];
			$category_data['update_user'] = $this->user_id;
			$category_data['update_datetime'] = time();

			$this->category_node_table = new B_Table($this->db, B_CATEGORY3_TABLE);
			$ret = $this->category_node_table->update($category_data);

			if($ret) {
				$this->db->commit();
			}
			if(!$ret) {
				$this->db->rollback();
			}

			return $ret;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_property.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/property.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
