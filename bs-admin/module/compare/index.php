<?php
/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	class compare_index extends B_AdminModule {
		function __construct() {
			parent::__construct(__FILE__);

			// MENU
			require_once('./config/menu_config.php');
			$this->menu = new B_Element($menu_config, $this->user_auth);
		}

		function init() {
			$version_id = $this->db->real_escape_string($_REQUEST['version_id']);

			$sql = "select * from " . B_DB_PREFIX . "version
					where version_id = '$version_id'";

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);

			$_SESSION[TERMINAL_ID]['version_right'] = $row;

			$sql = "select * from " . B_DB_PREFIX . "version
					where version_id = (
						select max(version_id)
						from " . B_DB_PREFIX . "version
						where version_id < '$version_id'
					)"; 

			$rs = $this->db->query($sql);
			$row = $this->db->fetch_assoc($rs);
			$_SESSION[TERMINAL_ID]['version_left'] = $row;

			$this->user_name = htmlspecialchars($this->user_name, ENT_QUOTES, B_CHARSET);
			$this->initial_page = DISPATCH_URL . '&module=contents&page=compare&method=init';
			$this->view_file = './view/view_index.php';

			$this->site_settings_table = new B_Table($this->db, 'settings');
			$this->site_settings = $this->site_settings_table->select();
			$this->title = B_TITLE_PREFIX . htmlspecialchars($this->site_settings['admin_site_title'], ENT_QUOTES, B_CHARSET);
			$this->site_title = $this->site_settings['admin_site_title'];
		}

		function view() {
			// Send HTTP header
			$this->sendHttpHeader();

			require_once($this->view_file);
		}
	}
