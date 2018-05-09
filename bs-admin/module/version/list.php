<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class version_list extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			// Create list header
			require_once('./config/list_header_config.php');
			$this->header = new B_Element($list_header_config, $this->user_auth);
			$obj = $this->header->getElementByName('version_info');
			$obj->value = $this->version_info;

			// Create DataGrid
			require_once('./config/list_config.php');
			$this->dg = new B_DataGrid($this->db, $list_config);

			// Create control elements
			$this->version_control = new B_Element($version_control_config, $this->user_auth);
			$this->version_control_confirm = new B_Element($version_control_confirm_config, $this->user_auth);
			$this->version_control_result = new B_Element($version_control_result_config, $this->user_auth);

			// Create version info
			$this->version_information = new B_Element($version_info_config, $this->user_auth);

			// Set max version_id
			$this->max_version_id = $this->getMaxVersionId();

			// Set call back
			$this->dg->setCallBack($this, '_list_callback');
		}

		function getMaxVersionId() {
			$sql = "select max(version_id) version_id from " . B_DB_PREFIX . "version";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			return $row['version_id'];
		}

		function func_default() {
			$this->init();
		}

		function init() {
			$this->session = '';

			$this->setProperty();
			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function select() {
			$this->setRequest();
			$this->setProperty();
			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function back() {
			$this->setProperty();

			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function jump() {
			$this->_setRequest('page_no');
			$this->setProperty();

			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function setRequest() {
			$this->_setRequest('page_no');
			$this->_setRequest('keyword');
			$this->_setRequest('row_per_page');
			return;
		}

		function setProperty() {
			$this->default_row_per_page = 20;

			$this->_setProperty('keyword', '');
			$this->_setProperty('page_no', 1);
			$this->_setProperty('row_per_page', $this->default_row_per_page);
		}

		function setHeader() {
			// Set header
			if($this->session) {
				$this->header->setValue($this->session);
			}

			$obj = $this->header->getElementByName('row_per_page');
			$obj->special_html.= ' data-default="' . $this->default_row_per_page . '"';
		}

		function setData() {
			$this->dg->setSqlWhere($this->sql_where);
			$this->dg->setRowPerPage($this->row_per_page);
			$this->dg->setSqlOrderBy(" order by version_id desc ");
			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			if($this->keyword) {
				$sql_where.= " and (version_id like '%KEYWORD%' or publication_datetime_t like '%KEYWORD%' or version like '%KEYWORD%' or notes like '%KEYWORD%') ";
				$sql_where = str_replace("%KEYWORD%", "%" . $this->db->real_escape_string_for_like($this->keyword) . "%", $sql_where);

				$select_message.= __('Keyword: ') . ' <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}

			if($select_message) {
				$select_message = '<p class="condition"><strong>' . __('Search conditions') . '&nbsp;</strong>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where;
			$this->select_message = $select_message;
			return;
		}

		function confirm() {
			if(!$this->post['reserved_version_id'] || !$this->post['working_version_id']) {
				$this->error_message.= '<span class="bold">' . __('Please select version.') . '</span>';
				$this->back();
			}
			else {
				$version_table = new B_Table($this->db, 'version');
				$this->_setRequest('reserved_version_id');
				$this->_setRequest('working_version_id');

				unset($this->session['reserved_datetime']);
				unset($this->session['publish_caution']);
				unset($this->session['publish_message']); 

				$row = $version_table->selectByPk(array('version_id' => $this->session['reserved_version_id']));
				$this->session['reserved_version_name'] = $row['version'];
				if($row['publication_datetime_u'] && $row['publication_datetime_u'] > time()) {
					$sql = "select * from " . B_DB_PREFIX . "v_current_version";
					$rs = $this->db->query($sql);
					$current_version = $this->db->fetch_assoc($rs);
					if($current_version['current_version_id'] != $this->session['reserved_version_id']) {
						$this->session['reserved_datetime'] = $row['publication_datetime_t'];
						$this->session['publish_caution'] = __('Scheduled to be published');
					}
					else {
						$this->session['publish_caution'] = __('published immediately');
						$this->session['publish_message'] = __('<img src="images/common/caution.png" alt="#" />If you schedule this version to be published at a later date, you must set the current published version');
					}
				}
				else {
					$this->session['publish_caution'] = __('Changes will be reflected immediately');
				}

				$row = $version_table->selectByPk(array('version_id' => $this->session['working_version_id']));
				$this->session['working_version_name'] = $row['version'];

				$this->version_information->setValue($this->session);

				$this->setView('view_confirm');
			}
		}

		function register() {
			$this->db->begin();

			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if(!$row) {
				$current_version_table = new B_Table($this->db, 'current_version');

				$param['id'] = '0000000001';
				$param['current_version_id'] = $this->session['reserved_version_id'];
				$param['reserved_version_id'] = $this->session['reserved_version_id'];
				$param['working_version_id'] = $this->session['working_version_id'];
				$param['create_datetime'] = time();
				$param['create_user'] = $this->user_id;

				$ret = $current_version_table->insert($param);
			}
			else {
				$param['id'] = '0000000001';
				if($row['current_version_id']) {
					$param['current_version_id'] = $row['current_version_id'];
				}
				else {
					$param['current_version_id'] = $this->session['reserved_version_id'];
				}
				$param['reserved_version_id'] = $this->session['reserved_version_id'];
				$param['working_version_id'] = $this->session['working_version_id'];
				$param['update_datetime'] = time();
				$param['update_user'] = $this->user_id;

				$this->current_version_table = new B_Table($this->db, 'current_version');
				$ret = $this->current_version_table->update($param);
			}

			// create cache from DB
			$row = $this->getCacheFromDB('working_version_id');
			$this->replaceCacheFile(B_FILE_INFO_W, B_FILE_INFO_SEMAPHORE_W, $row['cache_w']);

			$row = $this->getCacheFromDB('current_version_id');
			$this->replaceCacheFile(B_FILE_INFO_C, B_FILE_INFO_SEMAPHORE_C, $row['cache_c']);

			$this->createLimitFile(B_LIMIT_FILE_INFO, $row['publication_datetime_u']);

			if($ret) {
				$this->db->commit();
				$this->action_message = __('were set.');
			}
			else {
				$this->db->rollback();
				$this->action_message = __('were failed to set.');
			}

			// update version info
			$this->getVersionInfo();

			$this->version_information->setValue($this->session);

			$this->setView('view_result');
		}

		function _list_callback(&$array) {
			$row = &$array['row'];
			$version_id = $row->getElementByName('version_id');

			if($version_id->value == $this->max_version_id ) {
				$this->delete_disabled = true;

				$reserved_version_id = $row->getElementByName('reserved_version_id');
				$working_version_id = $row->getElementByName('working_version_id');
				$publication_status = $row->getElementByName('publication_status');

				if(!$reserved_version_id->checked && !$working_version_id->checked && $publication_status->value == '0') {
					$obj = $row->getElementByName('del_disable');
					$obj->display = 'none';
					$obj = $row->getElementByName('del_enable');
					$obj->display = 'block';
				}
			}

			$obj = $row->getElementByName('version_id');
			if($obj->value == '00001') {
				$obj = $row->getElementByName('compare_enable');
				$obj->display = 'none';
				$obj = $row->getElementByName('compare_disable');
				$obj->display = 'block';
			}
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_list.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/version.css">');
			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/selectbox_white.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}

		function view_confirm() {
			// Start buffering
			ob_start();

			require_once('./view/view_list_confirm.php');

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

		function view_result() {
			// Start buffering
			ob_start();

			require_once('./view/view_list_result.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			// Set css and javascript
			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/version.css">');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
