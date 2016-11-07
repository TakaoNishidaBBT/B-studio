<?php
/*
 * B-studio : Content Management System
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class article3_list extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			// List header
			require_once('./config/list_header_config.php');
			$this->header = new B_Element($list_header_config, $this->user_auth);

			// DataGrid
			require_once('./config/list_config.php');
			$this->dg = new B_DataGrid($this->db, $list_config);

			// Category list
			$this->category_list = $this->getCategoryList();
			$obj = $this->header->getElementByName('category_id');
			if($obj) {
				$obj->setProperty('data_set_value', $this->category_list);
			}
		}

		function getCategoryList() {
			// Create category_id list
			$root_node = new B_Node($this->db
									, B_CATEGORY3_TABLE
									, B_CATEGORY3_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, all
									, $this->session['open_nodes']);
			$list[''] = '  --  ';
			$list+= $root_node->getSelectNodeList();
			return $list;
		}

		function func_default() {
			$this->init();
		}

		function init() {
			$this->session = '';

			$this->setRequest();
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
			$this->_setRequest('category_id');
			$this->_setRequest('row_per_page');
			return;
		}

		function setProperty() {
			$this->default_row_per_page = 20;

			$this->_setProperty('keyword', '');
			$this->_setProperty('category_id', '');

			$this->_setProperty('page_no', 1);
			$this->_setProperty('row_per_page', $this->default_row_per_page);
			$this->_setProperty('sort_key', 'article_date');
			$this->_setProperty('order', ' desc');
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

			if($this->sort_key) {
				$sql_order_by = ' order by ' . $this->sort_key;
				$sql_order_by.= $this->order;
				$this->dg->setSortKey($this->sort_key);
				$this->dg->setSqlOrderBy($sql_order_by);
			}
			$this->dg->setRowPerPage($this->row_per_page);

			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			if($this->keyword) {
				$keyword = $this->db->real_escape_string_for_like($this->keyword);

				$sql = "select article_id from %DB_PREFIX%article3
						where title like '%KEYWORD%' or description like '%KEYWORD%'
						group by article_id";

				$sql = str_replace('%DB_PREFIX%', B_DB_PREFIX, $sql);
				$sql = str_replace('%KEYWORD%', "%" . $this->db->real_escape_string_for_like($this->keyword) . "%", $sql);

				$rs = $this->db->query($sql);
				for($i=0 ; $row = $this->db->fetch_assoc($rs) ; $i++) {
					if($sql_where_tmp) {
						$sql_where_tmp.= ",";
					}
					$sql_where_tmp.= "'" . $row['article_id'] . "'";
				}
				$sql_where_tmp.= ")";

				if($i) {
					$sql_where.= "and article_id in (";
					$sql_where.= $sql_where_tmp;
				}
				else {
					$sql_where.= " and 0=1 ";
				}

				$select_message.= __('Keyword: ') . ' <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}
			if($this->category_id) {
				$sql_where.= " and category_id = '%CATEGORY_ID%' ";
				$sql_where = str_replace('%CATEGORY_ID%', $this->category_id, $sql_where);
				$select_message.= __('Category: ') . '<em>' . htmlspecialchars(str_replace('&emsp;', '', $this->category_list[$this->category_id]), ENT_QUOTES, B_CHARSET) . '</em>&nbsp;&nbsp;';
			}

			if($select_message) {
				$select_message = '<p class="condition"><span class="bold">' . __('Search condition') . '&nbsp;</span>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where . $sql_where_invalid;
			$this->select_message = $select_message;
		}

		function rss() {
			require_once('./config/rss_config.php');
			$this->dg = new B_DataGrid($this->db, $rss_config);
			$this->dg->setPage('all');
			$this->dg->bind();
			$xml = $this->dg->getHtml();
			$fp = fopen('rss.xml', 'w');
			fwrite($fp, $xml);
			fclose($fp);
			exit;
		}

		function view() {
			// Start buffering
			ob_start();

			require_once('./view/view_list.php');

			// Get buffer
			$contents = ob_get_clean();

			// Send HTTP header
			$this->sendHttpHeader();

			// HTML ヘッダー出力
			$this->html_header->appendProperty('css', '<link href="css/article.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('css', '<link href="css/selectbox_white.css" type="text/css" rel="stylesheet" media="all" />');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js" type="text/javascript"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
