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

			// ヘッダー 作成
			require_once('./config/list_header_config.php');
			$this->header = new B_Element($list_header_config, $this->user_auth);
			$obj = $this->header->getElementByName('version_info');
			$obj->value = $this->version_info;

			// DataGrid 作成,
			require_once('./config/list_config.php');
			$this->dg = new B_DataGrid($this->db, $list_config);
			$this->version_control = new B_Element($version_control_config, $this->user_auth);
			$this->version_control_confirm = new B_Element($version_control_confirm_config, $this->user_auth);
			$this->version_control_result = new B_Element($version_control_result_config, $this->user_auth);

			// コールバック設定
			$this->dg->setTrCallBack($this, '_list_callback');
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
			// 表示件数
			$this->default_row_per_page = 10;

			$this->_setProperty('keyword', '');
			$this->_setProperty('page_no', 1);
			$this->_setProperty('row_per_page', $this->default_row_per_page);
		}

		function confirm() {
			$this->version_table = new B_Table($this->db, 'version');
			$this->reserved_version_id = $this->post['reserved_version'];
			$this->working_version_id = $this->post['working_version'];

			$row = $this->version_table->selectByPk(array('version_id' => $this->reserved_version_id));
			$this->reserved_version = $row['version'];
			if($row['publication_datetime_u'] && $row['publication_datetime_u'] > time()) {
				$sql = "select * from " . B_DB_PREFIX . "v_current_version";
				$rs = $this->db->query($sql);
				$current_version = $this->db->fetch_assoc($rs);
				if($current_version['current_version_id'] != $this->reserved_version_id) {
					$this->reserve_datetime = $row['publication_datetime_t'] . '予約登録';
				}
				else {
					$this->reserve_datetime = '即時反映（このバージョンを予約登録するにはそれまでに公開されるバージョンを設定してから再度、予約登録する必要があります）';
				}
			}
			else {
				$this->reserve_datetime = '即時反映';
			}

			$row = $this->version_table->selectByPk(array('version_id' => $this->working_version_id));
			$this->working_version = $row['version'];

			$this->_setRequest('reserved_version');
			$this->_setRequest('working_version');
			$this->session['reserve_datetime'] = $this->reserve_datetime;

			$this->session['reserved_version_name'] = $this->reserved_version;
			$this->session['working_version_name'] = $this->working_version;

			$this->setView('view_confirm');
		}

		function regist() {
			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			if(!$row) {
				$current_version_table = new B_Table($this->db, 'current_version');

				$param['id'] = '0000000001';
				$param['current_version_id'] = $this->session['reserved_version'];
				$param['reserved_version_id'] = $this->session['reserved_version'];
				$param['working_version_id'] = $this->session['working_version'];
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
					$param['current_version_id'] = $this->session['reserved_version'];
				}
				$param['reserved_version_id'] = $this->session['reserved_version'];
				$param['working_version_id'] = $this->session['working_version'];
				$param['update_datetime'] = time();
				$param['update_user'] = $this->user_id;

				$this->current_version_table = new B_Table($this->db, 'current_version');
				$ret = $this->current_version_table->update($param);
			}
			if($ret) {
				$this->db->commit();
				$message = '設定しました。';
			}
			else {
				$this->db->rollback();
				$message.= '更新に<strong><em>失敗</em></strong>しました。';
			}

			$sql = "select * from " . B_DB_PREFIX . "v_current_version";
			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			// ファイル情報シリアライズ
			$this->createCacheFile(B_FILE_INFO_W, B_FILE_INFO_SEMAPHORE_W, B_WORKING_RESOURCE_NODE_VIEW);
			$this->createCacheFile(B_FILE_INFO_C, B_FILE_INFO_SEMAPHORE_C, B_CURRENT_RESOURCE_NODE_VIEW);

			$this->createLimitFile(B_LIMIT_FILE_INFO, $row['publication_datetime_u']);

			$this->setView('view_result');
		}

		function setHeader() {
			$obj =& $this->header->getElementByName('default_row_per_page');
			$obj->value = $this->default_row_per_page;

			// ヘッダー情報設定
			if($this->session) {
				$this->header->setValue($this->session);
			}
		}

		function setData() {
			$this->dg->setSqlWhere($this->sql_where);
			$this->dg->setRowPerPage($this->row_per_page);
			$this->dg->setSqlOrderBy(" order by version_id desc ");

			// データバインド
			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			// 検索条件設定
			if($this->keyword) {
				$keyword = $this->util->quoteslahesforlike($this->keyword);

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

				$select_message.= 'キーワード： <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}

			if($select_message) {
				$select_message = '<p class="select_status"><strong>検索条件&nbsp;</strong>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where . $sql_where_invalid;
			$this->select_message = $select_message;
			return;
		}

		function _list_callback(&$array) {
			$row = &$array['row'];

			if(!$this->disabe_delete) {
				$this->disabe_delete = true;

				$reserved_version = $row->getElementByName('reserved_version');
				$working_version = $row->getElementByName('working_version');

				if($reserved_version->checked || $working_version->checked) {
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
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');

			// HTML ヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_list.php');
		}

		function view_confirm() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_list_confirm.php');
		}

		function view_result() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link href="css/version.css" type="text/css" rel="stylesheet" media="all" />');

			// HTMLヘッダー出力
			$this->showHtmlHeader();

			require_once('./view/view_list_result.php');
		}
	}
