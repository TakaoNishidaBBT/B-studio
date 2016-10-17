<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
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

			// Set call back
			$this->dg->setCallBack($this, '_list_callback');
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
			$this->_setRequest('page_no');
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
				$keyword = $this->db->real_escape_string_for_like($this->keyword);

				$sql = "select version_id from %DB_PREFIX%version
						where version like '%KEYWORD%' or memo like '%KEYWORD%'";

				$sql = str_replace("%DB_PREFIX%", B_DB_PREFIX, $sql);
				$sql = str_replace("%KEYWORD%", "%" . $keyword . "%", $sql);

				$rs = $this->db->query($sql);
				for($i=0 ; $row = $this->db->fetch_assoc($rs) ; $i++) {
					if($sql_where_tmp) {
						$sql_where_tmp.= ",";
					}
					$sql_where_tmp.= "'" . $row['version_id'] . "'";
				}
				$sql_where_tmp.= ")";

				if($i) {
					$sql_where.= "and version_id in (";
					$sql_where.= $sql_where_tmp;
				}
				else {
					$sql_where.= " and 0=1 ";
				}

				$select_message.= __('Keyword: ') . ' <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}

			if($select_message) {
				$select_message = '<p class="condition"><strong>' . __('Search condition') . '&nbsp;</strong>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where . $sql_where_invalid;
			$this->select_message = $select_message;
			return;
		}

		function confirm() {
			if(!$this->post['reserved_version_id'] || !$this->post['working_version_id']) {
				$this->error_message.= '<span class="bold">' . __('Please set versions.') . '</span>';
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
						$this->session['publish_message'] = __('<img src="images/common/caution.png" alt="#" />If you set scheduled to be published this version, you must set current published version');
					}
				}
				else {
					$this->session['publish_caution'] = __('published immediately');
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
			if($ret) {
				$this->db->commit();
				$this->action_message = __('were set.');
			}
			else {
				$this->db->rollback();
				$this->action_message = __('were failed to set.');
			}

			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			// Create cache files
			$this->createCacheFile(B_FILE_INFO_W, B_FILE_INFO_SEMAPHORE_W, B_WORKING_RESOURCE_NODE_VIEW);
			$this->createCacheFile(B_FILE_INFO_C, B_FILE_INFO_SEMAPHORE_C, B_CURRENT_RESOURCE_NODE_VIEW);

			$this->createLimitFile(B_LIMIT_FILE_INFO, $row['publication_datetime_u']);

			// update version info
			$this->getVersionInfo();

			$this->version_information->setValue($this->session);

			$this->setView('view_result');
		}

		function _list_callback(&$array) {
			$row = &$array['row'];

			if(!$this->disabe_delete) {
				$this->disabe_delete = true;

				$reserved_version_id = $row->getElementByName('reserved_version_id');
				$working_version_id = $row->getElementByName('working_version_id');
				$publication_status = $row->getElementByName('publication_status');

				if($reserved_version_id->checked || $working_versionid->checked || $publication_status->value != '0') {
					$obj = $row->getElementById('del_enable');
					$obj->display = 'none';
					$obj = $row->getElementById('del_disable');
					$obj->display = 'block';
				}
			}
			else {
				$obj = $row->getElementById('del_enable');
				$obj->display = 'none';
				$obj = $row->getElementById('del_disable');
				$obj->display = 'block';
			}

			$obj = $row->getElementByName('version_id');
			if($obj->value == '00001') {
				$obj = $row->getElementById('compare_enable');
				$obj->display = 'none';
				$obj = $row->getElementById('compare_disable');
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

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox_white.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');

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

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');

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

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
