<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class contents_property extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			require_once('./config/property_config.php');
			$this->form = new B_Element($form_config);
			$this->config_form = new B_Element($config_form_config);

			$obj = $this->form->getElementByName('config_form');
			$obj->addElement($this->config_form);

			$this->tab_control = new B_Element($tab_control_config);

			$this->contents_node_table = new B_Table($this->db, B_CONTENTS_NODE_TABLE);

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
			$sql = str_replace('%VIEW%', B_DB_PREFIX . B_WORKING_CONTENTS_NODE_VIEW, $sql);
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
			try {
				$this->form->setValue($this->post);

				if($this->post['node_id']) {
					$node_id = $this->post['node_id'];

					$sql = "select * from %CONTENTS_NODE_TABLE%
							where version_id = '%VERSION_ID%'
							and revision_id = '%REVISION_ID%'
							and node_id='$node_id'";

					$sql = str_replace('%CONTENTS_NODE_TABLE%', B_DB_PREFIX . B_CONTENTS_NODE_TABLE, $sql);
					$sql = str_replace('%VERSION_ID%', $this->version['working_version_id'], $sql);
					$sql = str_replace('%REVISION_ID%', $this->version['revision_id'], $sql);
					$rs = $this->db->query($sql);
					$row = $this->db->fetch_assoc($rs);

					if($row) {
						if($this->post['mode'] == 'confirm' && $row['update_datetime'] > $this->post['update_datetime']) {
							$this->status = true;
							$this->mode = 'confirm';
							$this->message = __("Another user has updated this record\nAre you sure you want to overwrite?");
						}
						else {
							$this->update($node_id);
						}
					}
					else {
						$this->insert($node_id);
					}
				}
				else {
					$this->selectInsert($node_id);
				}
			}
			catch(Exception $e) {
				$this->status = false;
				$this->mode = 'alert';
				$this->message = $e->getMessage();
			}

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

		function update($node_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($node_data);
			$node_data['node_id'] = $node_id;
			$node_data['version_id'] = $this->version['working_version_id'];
			$node_data['revision_id'] = $this->version['revision_id'];
			$node_data['update_user'] = $this->user_id;
			$node_data['update_datetime'] = time();

			$ret = $this->contents_node_table->update($node_data);

			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = __('Saved');
			}
			if(!$ret) {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
			}
		}

		function insert($node_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($node_data);

			$node_data['node_id'] = $node_id;
			$node_data['create_user'] = $this->user_id;
			$node_data['create_datetime'] = time();
			$node_data['update_user'] = $this->user_id;
			$node_data['update_datetime'] = time();
			$node_data['del_flag'] = '0';
			$node_data['version_id'] = $this->version['working_version_id'];
			$node_data['revision_id'] = $this->version['revision_id'];
			$node_data['contents_id'] = $contents_id;

			$ret = $this->contents_node_table->insert($contents_data);

			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = __('Saved');
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
			}
		}

		function selectInsert($node_id) {
			// start transaction
			$this->db->begin();

			$this->form->getValue($node_data);

			$node_data['create_user'] = $this->user_id;
			$node_data['create_datetime'] = time();
			$node_data['update_user'] = $this->user_id;
			$node_data['update_datetime'] = time();
			$node_data['del_flag'] = '0';
			$node_data['version_id'] = $this->version['working_version_id'];
			$node_data['revision_id'] = $this->version['revision_id'];

			$ret = $this->contents_node_table->selectInsert($node_data);

			if($ret) {
				$this->db->commit();
				$this->status = true;
				$this->message = __('Saved');
			}
			else {
				$this->db->rollback();
				$this->status = false;
				$this->message =  __('Failed to save');
			}
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_property.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/property.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_tab.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
