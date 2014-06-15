<?php
/*
 * B-studio : Contents Management System
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class user_list extends B_AdminModule {
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

			// コールバック設定
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

		function sort() {
			if($this->request['sort_key']) {
				if(isset($this->session['sort_key']) && 
					$this->session['sort_key'] == $this->request['sort_key']) {
					if($this->session['order'] == ' asc') {
						$this->session['order'] = ' desc';
					}
					else {
						$this->session['order'] = ' asc';
					}
				}
				else {
					$this->session['sort_key'] = $this->request['sort_key'];
					$this->session['order'] = ' asc';
				}
			}
			$this->setProperty();

			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function setRequest() {
			$this->_setRequest('page_no');
			$this->_setRequest('keyword');
			$this->_setRequest('row_per_page');
			$this->_setRequest('invalid_data');
		}

		function setProperty() {
			// 表示件数
			$this->default_row_per_page = 10;

			$this->_setProperty('keyword', '');

			$this->_setProperty('page_no', 1);
			$this->_setProperty('invalid_data', '');
			$this->_setProperty('row_per_page', $this->default_row_per_page);
			$this->_setProperty('sort_key', 'user_id');
			$this->_setProperty('order', ' asc');
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
			// where句　登録
			$this->dg->setSqlWhere($this->sql_where);

			// ソート
			if($this->sort_key) {
				$sql_order_by = ' order by '.$this->sort_key;
				$sql_order_by.= $this->order;
				$this->dg->setSortKey($this->sort_key);
				$this->dg->setSqlOrderBy($sql_order_by);
			}
			$this->dg->setRowPerPage($this->row_per_page);

			// データバインド
			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			// 検索条件設定
			if($this->invalid_data) {
				$sql_where_invalid = "";
				$select_message.= '<em>有効データのみ</em>　';
				$sql_where_invalid = " and status = '01' ";
			}

			if($this->keyword) {
				$sql = "select id from %DB_PREFIX%user
						where
						user_id like '%KEYWORD%' or
						f_name like '%KEYWORD%' or
						g_name like '%KEYWORD%' or
						memo like '%KEYWORD%'
						group by user_id";

				$sql = str_replace("%DB_PREFIX%", B_DB_PREFIX, $sql);
				$sql = str_replace("%KEYWORD%", "%" . $this->db->quoteslahesforlike($this->keyword) . "%", $sql);

				$rs = $this->db->query($sql);
				for($i=0 ; $row = $this->db->fetch_assoc($rs) ; $i++) {
					if($sql_where_tmp) {
						$sql_where_tmp.= ",";
					}
					$sql_where_tmp.= "'" . $row['id'] . "'";
				}
				$sql_where_tmp.= ")";

				if($i) {
					$sql_where.= "and id in (";
					$sql_where.= $sql_where_tmp;
				}
				else {
					$sql_where.= " and 0=1 ";
				}

				$select_message.= 'キーワード： <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}

			if($select_message) {
				$select_message = '<strong>検索条件&nbsp;</strong>' . $select_message;
			}

			$this->sql_where = $sql_where . $sql_where_invalid;
			$this->select_message = $select_message;
		}

		function _list_callback(&$array) {
			$row = &$array['row'];

			$status = &$row->getElementByName('user_status');
			if($status->value == '9') {
				$row->start_html = $row->start_html_invalid;
			}
		}

		function view() {
			// HTTPヘッダー出力
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/user.css" type="text/css" rel="stylesheet" media="all">');
			$this->showHtmlHeader();

			require_once('./view/view_list.php');
		}
	}
