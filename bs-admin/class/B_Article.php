<?php
/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class B_Article
	// 
	// -------------------------------------------------------------------------
	class B_Article {
		function __construct($db, $view) {
			$this->db = $db;
			$this->view = $view;
		}

		function setSqlGroupBy($sql_string) {
			$this->sql_group_by = $sql_string;
		}

		function setSqlOrderBy($sql_string) {
			$this->sql_order_by = $sql_string;
		}

		function setSortKey($sort_key) {
			$this->sort_key = $sort_key;
		}

		function setSqlWhere($sql_string) {
			$this->sql_where = $sql_string;
		}

		function setSqlLimit($sql_string) {
			$this->sql_limit = $sql_string;
		}

		function setRowPerPage($cnt) {
			$this->pager['row_per_page'] = $cnt;
		}

		function sqlReplace($search, $replace) {
			$this->select_sql = str_replace($search, $replace, $this->select_sql);
			$this->count_sql = str_replace($search, $replace, $this->count_sql);
		}

		function setPage($page_no) {
			$this->page_no = $page_no;
		}

		function bind() {
			$this->select_sql = "select * from " . B_DB_PREFIX . $this->view;
		}
	}
