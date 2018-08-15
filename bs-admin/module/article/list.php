<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class article_list extends B_AdminModule {
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
			$obj = $this->header->getElementByName('category');
			if($obj) {
				$obj->setProperty('data_set_value', $this->category_list);
			}
		}

		function getCategoryList() {
			// Create category list
			$root_node = new B_Node($this->db
									, B_CATEGORY_TABLE
									, B_CATEGORY_VIEW
									, $this->version['working_version_id']
									, $this->version['revision_id']
									, 'root'
									, null
									, 'all'
									, null
									, true);
			$list[''] = '  --  ';
			$list+= $root_node->getSelectNodeList();
			return $list;
		}

		function func_default() {
			$this->init();
		}

		function init() {
			$this->session = array();

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
			$this->_setRequest('category');
			$this->_setRequest('row_per_page');
			return;
		}

		function setProperty() {
			$this->default_row_per_page = 20;

			$this->_setProperty('keyword', '');
			$this->_setProperty('category', '');

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
				$this->dg->setSortKey($this->sort_key);
				$this->dg->setSortOrder($this->order);

				$sql_order_by = ' order by ' . $this->sort_key;
				$sql_order_by.= $this->order;
				$this->dg->setSqlOrderBy($sql_order_by);
			}
			$this->dg->setRowPerPage($this->row_per_page);

			$this->dg->setPage($this->page_no);
			$this->dg->bind();
		}

		function setSqlWhere() {
			if($this->keyword) {
				$keyword = $this->db->real_escape_string_for_like($this->keyword);
				$sql_where.= " and (article_id like '%KEYWORD%' or category like '%KEYWORD%' or permalink like '%KEYWORD%' or article_date_t like '%KEYWORD%' or title like '%KEYWORD%' or url like '%KEYWORD%' or keywords like '%KEYWORD%' or description like '%KEYWORD%' or content1 like '%KEYWORD%' or content2 like '%KEYWORD%' or content3 like '%KEYWORD%' or content4 like '%KEYWORD%') ";
				$sql_where = str_replace('%KEYWORD%', "%" . $this->db->real_escape_string_for_like($this->keyword) . "%", $sql_where);

				$select_message.= __('Keyword: ') . ' <em>' . htmlspecialchars($this->keyword, ENT_QUOTES) . '</em>　';
			}
			if($this->category) {
				$c = explode(',', $this->db->real_escape_string($this->category));
				$category_id = $c[0];
				$category_path = $c[1] . $c[0] . '/%';

				$sql_where.= " and (category_id = '%CATEGORY_ID%' or path like '%CATEGORY_PATH%') ";
				$sql_where = str_replace('%CATEGORY_ID%', $category_id, $sql_where);
				$sql_where = str_replace('%CATEGORY_PATH%', $category_path, $sql_where);

				$select_message.= __('Category: ') . '<em>' . htmlspecialchars(str_replace('&emsp;', '', $this->category_list[$this->category]), ENT_QUOTES, B_CHARSET) . '</em>&nbsp;&nbsp;';
			}

			if($select_message) {
				$select_message = '<p class="condition"><span class="bold">' . __('Search conditions') . '&nbsp;</span>' . $select_message . '</p>';
			}

			$this->sql_where = $sql_where;
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

			// Set css and javascript
			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/article.css">');
			$this->html_header->appendProperty('css', '<link rel="stylesheet" href="css/selectbox.css">');
			$this->html_header->appendProperty('script', '<script src="js/bframe_selectbox.js"></script>');

			// Show HTML header
			$this->showHtmlHeader();

			// Show HTML body
			echo $contents;
		}
	}
