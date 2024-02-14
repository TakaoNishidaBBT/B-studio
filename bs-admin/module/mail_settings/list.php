<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class mail_settings_list extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			// Create list header
			require_once('./config/list_header_config.php');
			$this->header = new B_Element($header_config, $this->user_auth);

			// Create DataGrid
			require_once('./config/list_config.php');
			$this->dg = new B_DataGrid($this->db, $list_config);
		}

		function func_default() {
			$this->init();
		}

		function init() {
			$this->session = array();

			$this->setProperty();
			$this->setHeader();
			$this->setSqlWhere();
			$this->setData();
		}

		function select() {
			$this->setRequest();
			$this->setProperty();

			// allways
			$this->page_no = 1;

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
			$this->_setProperty('sort_key', 'mail_id');
			$this->_setProperty('order', ' asc');
		}

		function setHeader() {
			// Set header
			if($this->session) {
				$this->header->setValue($this->session);
			}

			$obj = $this->header->getElementByName('row_per_page');
			$obj->attr.= ' data-default="' . $this->default_row_per_page . '"';
		}

		function setData() {
			$this->dg->setSqlWhere($this->sql_where);

			if($this->sort_key) {
				$this->dg->setSortKey($this->sort_key);
				$this->dg->setSortOrder($this->order);

				$sql_order_by = ' order by '. $this->sort_key;
				$sql_order_by.= $this->order;
				$this->dg->setSqlOrderBy($sql_order_by);
			}
			$this->dg->setRowPerPage($this->row_per_page);

			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			$select_message = '';
			$sql_where = '';

			if($this->keyword) {
				$sql_where.= " and (mail_id like '%KEYWORD%' or mail_title like '%KEYWORD%' or subject like '%KEYWORD%' or from_name like '%KEYWORD%' or from_addr like '%KEYWORD%'
								 or to_addr like '%KEYWORD%' or bcc like '%KEYWORD%' or body like '%KEYWORD%' or html like '%KEYWORD%') ";
				 = str_replace("%KEYWORD%", "%" . $this->db->real_escape_string_for_like($this->keyword) . "%", $sql_where);

				$select_message.= __('Keyword: ') . ' <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}

			if($select_message) {
				$select_message = '<p class="condition"><span class="bold">' . __('Search conditions') . '&nbsp;</span>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where;
			$this->select_message = $select_message;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_list.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/mail_settings.css">');
			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/selectbox.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
